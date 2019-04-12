<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Resources\Appointment as AppointmentResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @Return AppointmentResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AppointmentResource::collection(Appointment::Where('status','=','cart')->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @Return AppointmentResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getById($id)
    {
        return AppointmentResource::collection(Appointment::Where('patient_id','=',$id)->where('status','=','cart')->get());
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
        try{
            $cart = Appointment::create([
                'doctor_id' => $request->doctor_id,
                'patient_id' => $request->doctor_id,
                'room_id' => $request->room_id, // or find available room
                'start' => $request->start,
                'end' => $request->end,
                'type' => $request->type,
                'status' => $request->status,
            ]);
            return new AppointmentResource($cart);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Redirect to checkout page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCheckoutPage()
    {
        return view('appointment.checkout');
    }
}
