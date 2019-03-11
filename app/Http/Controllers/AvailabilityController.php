<?php

namespace App\Http\Controllers;

use App\Availability;
use App\Http\Resources\Availability as AvailabilityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:doctor')->only('showAddAvailabilityPage');
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
        return AvailabilityResource::collection(Availability
            ::availableIs($request->is_available)
            ->startAfter($request->start)
            ->endBefore($request->end)
            ->DoctorId($request->doctor_id)
            ->paginate($request->per_page ?? 50));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return AvailabilityResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function selectDate(Request $request)
    {
        $date = $request->get('date');
        return AvailabilityResource::collection(Availability::where('start','=', $date)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            //rules go here
        ]);

        try{
            $availability = Availability::create([
                'doctor_id' => $validated['doctor_id'],
                'start' => $validated['start'],
                'end' => $validated['end'],
                'is_available' => $validated['is_available'] ?? 1,
                'reason_of_unavailability' => $validated['reason_of_unavailability'] ?? null
            ]);
            return new AvailabilityResource($availability);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Availability  $availability
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
        return view('doctor.addAvailability');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Availability  $availability
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
     * @param  \App\Availability  $availability
     * @return AvailabilityResource|\Illuminate\Http\Response
     */
    public function update(Request $request, Availability $availability)
    {
        $validated = $request->validate([
            //rules go here
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($validated['doctor_id']) ?: $availability->doctor_id = $validated['doctor_id'];
        !isset($validated['start']) ?: $availability->start = $validated['start'];
        !isset($validated['end']) ?: $availability->end = $validated['end'];
        !isset($validated['is_available']) ?: $availability->is_available = $validated['is_available'];
        !isset($validated['reason_of_unavailability']) ?:
            $availability->reason_of_unavailability = $validated['reason_of_unavailability'];

        $availability->save();
        return new AvailabilityResource($availability);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Availability  $availability
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
