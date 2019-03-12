<?php

namespace App\Http\Controllers;

use App\Room;
use App\Http\Resources\Room as RoomResource;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RoomResource::collection(Room::paginate($request->per_page ?? 5));
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
     * @return \Illuminate\Http\Response|RoomResource
     */
    public function store(Request $request)
    {
        try{
            $room = Room::create([
                'doctor_id' => $request->doctor_id,
                'start' => $request->start,
                'end' => $request->end,
                'is_available' => $request->is_available ?? 1,
                'reason_of_unavailability' => $request->reason_of_unavailability ?? null
            ]);
            return new RoomResource($room);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response|\App\Http\Resources\Room
     */
    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response|\App\Http\Resources\Room
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($validated['number']) ?: $room->number = $validated['number'];
        !isset($validated['name']) ?: $room->name = $validated['name'];

        $room->save();
        return new RoomResource($room);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        try {
            return response()->json(['success' => $room->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
