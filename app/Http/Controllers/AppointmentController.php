<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Doctor;
use App\User as Patient;
use App\Room;
use App\Http\Resources\Appointment as AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return AppointmentResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $appointments = Appointment::ofDoctorId($request->doctor_id ?? auth('doctor')->id())
            ->ofStatus($request->status);

        if ($request->exists('start') || $request->exists('end')) {
            $appointments = $appointments->between($request->start, $request->end);
        }

        return AppointmentResource::collection($appointments->paginate($request->per_page ?? 50));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //exit
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return AppointmentResource|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'    => 'required|int',
            'patient_id'   => auth('web')->check() ? 'nullable|int' : 'required|int',
            'room_id'      => 'nullable|int',
            'start'        => 'required|before_or_equal:end',
            'end'          => 'required|after_or_equal:start',
            'type' => ['required', Rule::in(['walk-in','annual checkup'])],
            'status' => ['required', Rule::in(['active','cart','cancelled','complete','cart'])]
        ]);

        try {
            $patient = Patient::findOrFail($validated['patient_id'] ?? Auth::guard('web')->id());

            if($patient->has_annual_checkup && $validated['status'] === '') {

            }

            $appointment = Appointment::create([
                    'doctor_id' => $validated['doctor_id'],
                    'patient_id' => $patient->id,
                    'room_id' => $validated['room_id'] ?? Room::all()->random()->id, // or find available room
                    'start' => $validated['start'],
                    'end' => $validated['end'],
                    'type' => $validated['type'] ?? 'walk-in',
                    'status' => $validated['status'] ?? 'cart',
                ]);
            return new AppointmentResource($appointment);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return AppointmentResource
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }

    /**
     * Redirect to appointment page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreateAppointmentPage()
    {
        return view('appointment.appointment');
    }

    /**
     * Redirect to appointment page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showViewAppointmentsPage()
    {
        return view('appointment.viewAppointments');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return AppointmentResource|\Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            //rules go here
            'doctor_id' => 'required|int|max:10',
            'patient_id' => 'required|int|max:10',
            'room_id' => 'required|int|max:10',
            'start' => 'date',
            'end' => 'date',
            'type' => ['required',Rule::in(['walk-in','annual checkup'])],
            'status' => ['required',Rule::in(['active','cart','cancelled','complete','cart'])]
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($validated['doctor_id']) ?: $appointment->doctor_id = $validated['doctor_id'];
        !isset($validated['patient_id']) ?: $appointment->patient_id = $validated['patient_id'];
        !isset($validated['room_id']) ?: $appointment->room_id = $validated['room_id'];
        !isset($validated['start']) ?: $appointment->start = $validated['start'];
        !isset($validated['end']) ?: $appointment->end = $validated['end'];
        !isset($validated['type']) ?: $appointment->type = $validated['type'];
        !isset($validated['status']) ?: $appointment->status = $validated['status'];

        $appointment->save();
        return new AppointmentResource($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return AppointmentResource|\Illuminate\Http\Response
     */
    public function finalize($id)
    {
        $cart = Appointment::Where('patient_id', '=', $id)->where('status', '=', 'cart')->get();

        foreach ($cart as $cartItem) {
            $cartItem->status = 'active';
            $cartItem->save();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return AppointmentResource|\Illuminate\Http\Response
     */
    public function cancelAppointment($id)
    {
        $appointments = Appointment::Where('id', '=', $id)->get();

        foreach ($appointments as $appointment) {
            $appointment->status = 'cancelled';
            $appointment->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        try {
            return response()->json(['success' => $appointment->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
