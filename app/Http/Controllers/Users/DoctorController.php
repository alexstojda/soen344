<?php

namespace App\Http\Controllers\Users;

use App\Models\Doctor;
use App\Http\Resources\Doctor as DoctorResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return DoctorResource::collection(Doctor::paginate($request->per_page ?? 5));
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
     * @return \Illuminate\Http\Response|\App\Http\Resources\Doctor
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'permit_id' => 'required|int|max:11',
                'clinic_id' =>'required|int|max:255',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'speciality' => 'required|string',
                'city' => 'required|string',
                'email' => 'required|email',
                'password' =>'required|string',
            ]);

            $doctor = Doctor::create([
                'permit_id' => $data['permit_id'],
                'clinic_id' =>'required|int|max:255',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'speciality' => $data['speciality'],
                'city' => $data['city'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return new DoctorResource($doctor);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Doctor  $doctor
     * @return \Illuminate\Http\Response|\App\Http\Resources\Doctor
     */
    public function show(Doctor $doctor)
    {
        return new DoctorResource($doctor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Doctor  $doctor
     * @return \Illuminate\Http\Response|\App\Http\Resources\Doctor
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'permit_id' => 'required|int|max:11',
            'clinic_id' =>'required|int|max:255',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'speciality' => 'required|string',
            'city' => 'required|string',
            'email' => 'required|email',
            'password' =>'required|string'
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($data['permit_id']) ?: $doctor->permit_id = $data['permit_id'];
        !isset($data['clinic_id']) ?: $doctor->clinic_id = $data['clinic_id'];
        !isset($data['first_name']) ?: $doctor->first_name = $data['first_name'];
        !isset($data['last_name']) ?: $doctor->last_name = $data['last_name'];
        !isset($data['speciality']) ?: $doctor->speciality = $data['speciality'];
        !isset($data['city']) ?: $doctor->city = $data['city'];
        !isset($data['email']) ?: $doctor->email = $data['email'];
        !isset($data['password']) ?: $doctor->password = Hash::make($data['password']);

        $doctor->save();
        return new DoctorResource($doctor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        try {
            return response()->json(['success' => $doctor->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
