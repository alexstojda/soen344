<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\Http\Resources\Patient as PatientResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PatientResource::collection(User::paginate($request->per_page ?? 5));
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
     * @return \Illuminate\Http\Response|\App\Http\Resources\Patient
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                //rules go here
            ]);

            $nurse = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return new PatientResource($nurse);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $patient
     * @return \Illuminate\Http\Response|\App\Http\Resources\Patient
     */
    public function show(User $patient)
    {
        return new PatientResource($patient);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(User $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $patient
     * @return \Illuminate\Http\Response|\App\Http\Resources\Patient
     */
    public function update(Request $request, User $patient)
    {
        $data = $request->validate([
            //rules go here
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($data['name']) ?: $patient->name = $data['name'];
        !isset($data['email']) ?: $patient->email = $data['email'];
        !isset($data['password']) ?: $patient->password = Hash::make($data['password']);

        $patient->save();
        return new PatientResource($patient);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $patient)
    {
        try {
            return response()->json(['success' => $patient->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
