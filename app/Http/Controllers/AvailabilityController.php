<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Http\Resources\Availability as AvailabilityResource;
use DateTime;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:doctor,nurse')->only(['create','store','edit','update','destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return AvailabilityResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $availabilities = Availability::ofDoctorId($request->doctor_id ?? auth('doctor')->id());

        if ($request->available ?? true) {
            $availabilities = $availabilities->available();
        } elseif (!$request->available) {
            $availabilities = $availabilities->unavailable();
        }

        if ($request->exists('date')) {
            $availabilities = $availabilities->between($request->date);
        }

        if ($request->exists('start') || $request->exists('end')) {
            $availabilities = $availabilities->startAfter($request->start)->endBefore($request->end);
        }

        if ($request->exists('consecutive')) {
            $availabilities = $availabilities->consecutive($request->consecutive, $request->operator);
        } elseif ($request->exists('length')) {
            $availabilities = $availabilities->length($request->length . 'min', $request->operator);
        }

        return AvailabilityResource::collection($availabilities->paginate($request->per_page ?? 50));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return AvailabilityResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function selectDate($date)
    {
        $formattedDate = new DateTime($date);
        $today = $date;
        $tomorrow = date_format(date_add($formattedDate, date_interval_create_from_date_string("1 days")), 'Y-m-d');
        return AvailabilityResource::collection(Availability::whereBetween('start', [$today, $tomorrow])->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doctor.addAvailability');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return AvailabilityResource|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'    => auth('doctor')->check() ? 'nullable|int' : 'required|int',
            'start'        => 'required|before_or_equal:end',
            'end'          => 'required|after_or_equal:start',
            'is_working' => 'nullable|boolean',
            'message' => 'nullable|string|min:5|max:255',
        ]);

        try {
            $availability = Availability::create([
                'doctor_id' => $validated['doctor_id'] ?? auth('doctor')->id(),
                'start' => $validated['start'],
                'end' => $validated['end'],
                'is_working' => $validated['is_working'] ?? 1,
                'message' => $validated['message'] ?? null
            ]);
            return new AvailabilityResource($availability);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Availability  $availability
     * @return AvailabilityResource
     */
    public function show(Availability $availability)
    {
        return new AvailabilityResource($availability);
    }

    /**
     * Redirect to appointment page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddAvailabilityPage()
    {
        return view('doctor.availability.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function edit(Availability $availability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Availability  $availability
     * @return AvailabilityResource|\Illuminate\Http\Response
     */
    public function update(Request $request, Availability $availability)
    {
        $validated = $request->validate([
            'doctor_id'    => 'nullable|int',
            'start'        => 'required|before_or_equal:end',
            'end'          => 'required|after_or_equal:start',
            'is_working' => 'required|boolean',
            'message' => 'nullable|string|min:5|max:255',
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($validated['doctor_id']) ? auth('doctor')->id() : $availability->doctor_id = $validated['doctor_id'];
        !isset($validated['start']) ?: $availability->start = $validated['start'];
        !isset($validated['end']) ?: $availability->end = $validated['end'];
        !isset($validated['is_working']) ?: $availability->is_working = $validated['is_working'];
        !isset($validated['message']) ?:
            $availability->message = $validated['message'];

        $availability->save();
        return new AvailabilityResource($availability);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function destroy(Availability $availability)
    {
        try {
            return response()->json(['success' => $availability->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
