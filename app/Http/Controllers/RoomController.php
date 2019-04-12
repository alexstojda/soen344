<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Resources\Room as RoomResource;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $rooms = Room::query();
        if ($request->exists('start') || $request->exists('end')) {
            $rooms = Room::availableBetween($request->start, $request->end);
        }

        return RoomResource::collection($rooms->paginate($request->per_page ?? 5));
    }

    /**s
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
        try {
            $data = $request->validate([
                'number' => 'required|string|max:255',
                'name'   => 'required|string|max:255',
                'clinic_id' => 'required|int|max:255',
            ]);

            $room = Room::create([
                'number' => $data['number'],
                'name' => $data['name'],
                'clinic_id' => $data['clinic_id'],
            ]);
            return new RoomResource($room);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response|\App\Http\Resources\Room
     */
    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Room  $room
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
     * @param  Room  $room
     * @return \Illuminate\Http\Response|\App\Http\Resources\Room
     */
    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'number' => 'string|max:255',
            'name'   => 'string|max:255',
            'clinic_id' => 'int|max:255',
        ]);

        !isset($data['number']) ?: $room->number = $data['number'];
        !isset($data['name']) ?: $room->name = $data['name'];
        !isset($data['clinic_id']) ?: $room->clinic_id = $data['clinic_id'];

        $room->save();
        return new RoomResource($room);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Room  $room
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
