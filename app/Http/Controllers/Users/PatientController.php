<?php

namespace App\Http\Controllers\Users;

use App\Models\User as Patient;
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
        return PatientResource::collection(Patient::paginate($request->per_page ?? 5));
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
                'name' => 'required|string|max:255',
                'health_card_number' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'gender' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8'
            ]);

            $nurse = Patient::create([
                'name' => $data['name'],
                'health_card_number' => $data['health_card_number'],
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return new PatientResource($nurse);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Patient  $patient
     * @return \Illuminate\Http\Response|\App\Http\Resources\Patient
     */
    public function show(Patient $patient)
    {
        return new PatientResource($patient);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Patient  $patient
     * @return \Illuminate\Http\Response|\App\Http\Resources\Patient
     */
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'health_card_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($data['name']) ?: $patient->name = $data['name'];
        !isset($data['health_card_number']) ?: $patient->name = $data['health_card_number'];
        !isset($data['address']) ?: $patient->name = $data['address'];
        !isset($data['phone_number']) ?: $patient->name = $data['phone_number'];
        !isset($data['birth_date']) ?: $patient->name = $data['birth_date'];
        !isset($data['gender']) ?: $patient->name = $data['gender'];
        !isset($data['email']) ?: $patient->email = $data['email'];
        !isset($data['password']) ?: $patient->password = Hash::make($data['password']);

        $patient->save();
        return new PatientResource($patient);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        try {
            return response()->json(['success' => $patient->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
