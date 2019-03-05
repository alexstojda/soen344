<?php

namespace App\Http\Controllers;

use App\cart;
use App\Http\Resources\Cart as CartResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @Return CartResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CartResource::collection(Cart::paginate($request->perPage ?? 50));
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
     * @return CartResource|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $cart = Cart::create([
                'doctor_id' => $request->doctor_id,
                'patient_id' => $request->doctor_id,
                'room_id' => $request->room_id, // or find available room
                'start' => $request->start,
                'end' => $request->end,
                'type' => $request->type,
                'status' => $request->status,
            ]);
            return new CartResource($cart);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cart  $cart
     * *@return CartResource
     */
    public function show(Cart $cart)
    {
        return new CartResource($cart);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cart  $cart
     * @return CartResource|\Illuminate\Http\Response
     */
    public function update(Request $request, cart $cart)
    {
        $validated = $request->validate([
            //rules go here
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($validated['doctor_id']) ?: $cart->doctor_id = $validated['doctor_id'];
        !isset($validated['patient_id']) ?: $cart->patient_id = $validated['patient_id'];
        !isset($validated['room_id']) ?: $cart->room_id = $validated['room_id'];
        !isset($validated['start']) ?: $cart->start = $validated['start'];
        !isset($validated['end']) ?: $cart->end = $validated['end'];
        !isset($validated['type']) ?: $cart->type = $validated['type'];
        !isset($validated['status']) ?: $cart->status = $validated['status'];

        $cart->save();
        return new CartResource($cart);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(cart $cart)
    {
        try {
            return response()->json(['success' => $cart->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
