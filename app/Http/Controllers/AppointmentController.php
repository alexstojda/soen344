<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Doctor;
use App\Room;
use App\Http\Resources\Appointment as AppointmentResource;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AvailabilityResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AppointmentResource::collection(Appointment::paginate($request->per_page ?? 50));
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
     * @return AppointmentResource|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart = $request->get('cart');

        foreach($cart as $cartItem)
        {
            try {
                $appointment = Appointment::create([
                    'doctor_id' => $cartItem->doctor_id,
                    'patient_id' => $cartItem->patient_id,
                    'room_id' => $cartItem->room_id, // or find available room
                    'start' => $cartItem->start,
                    'end' => $cartItem->end,
                    'type' => $cartItem->type,
                    'status' => $cartItem->status,
                ]);
                return new AppointmentResource($appointment);
            } catch (\Exception $e) {
                return response()->json($e);
            }
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
            'type' => ['required',Rule::in(['walk-in','annual checkup','regular','urgent'])],
            'status' => ['required',Rule::in(['active','cancelled','complete'])]
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
