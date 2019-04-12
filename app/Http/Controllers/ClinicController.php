<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use \App\Http\Resources\Clinic as ClinicResource;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->per_page)
            return ClinicResource::collection(Clinic::paginate($request->per_page ?? 5));
        else
            return ClinicResource::collection(Clinic::all());
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
     * @return ClinicResource|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'open' => 'required',
                'close' => 'required',
            ]);

            $clinic = Clinic::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'open' => $data['open'],
                'close' => $data['close'],
            ]);
            return new ClinicResource($clinic);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return ClinicResource|\Illuminate\Http\Response
     */
    public function show(Clinic $clinic)
    {
        return new ClinicResource($clinic);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return \Illuminate\Http\Response
     */
    public function edit(Clinic $clinic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clinic  $clinic
     * @return ClinicResource|\Illuminate\Http\Response
     */
    public function update(Request $request, Clinic $clinic)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'open' => 'required|date_format:H:m:s',
            'close' => 'required|date_format:H:m:s',
        ]);

        !isset($data['name']) ?: $clinic->name = $data['name'];
        !isset($data['address']) ?: $clinic->address = $data['address'];
        !isset($data['phone']) ?: $clinic->phone = $data['phone'];
        !isset($data['open']) ?: $clinic->open = $data['open'];
        !isset($data['close']) ?: $clinic->close = $data['close'];

        $clinic->save();
        return new ClinicResource($clinic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clinic $clinic)
    {
        try {
            return response()->json(['success' => $clinic->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
