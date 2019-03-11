<?php

namespace App\Http\Controllers\Users;

use App\Nurse;
use App\Http\Resources\Nurse as NurseResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NurseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return NurseResource::collection(Nurse::paginate($request->per_page ?? 5));
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
     * @return \Illuminate\Http\Response|\App\Http\Resources\Nurse
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                //rules go here
            ]);

            $nurse = Nurse::create([
                'access_id' => $data['access_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return new NurseResource($nurse);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nurse  $nurse
     * @return \Illuminate\Http\Response|\App\Http\Resources\Nurse
     */
    public function show(Nurse $nurse)
    {
        return new NurseResource($nurse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nurse  $nurse
     * @return \Illuminate\Http\Response
     */
    public function edit(Nurse $nurse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nurse  $nurse
     * @return \Illuminate\Http\Response|\App\Http\Resources\Nurse
     */
    public function update(Request $request, Nurse $nurse)
    {
        $data = $request->validate([
            //rules go here
        ]);
        // if it's not valid the code will stop here and throw the error with required fields

        !isset($data['access_id']) ?: $nurse->access_id = $data['access_id'];
        !isset($data['name']) ?: $nurse->name = $data['name'];
        !isset($data['email']) ?: $nurse->email = $data['email'];
        !isset($data['password']) ?: $nurse->password = Hash::make($data['password']);

        $nurse->save();
        return new NurseResource($nurse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nurse  $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nurse $nurse)
    {
        try {
            return response()->json(['success' => $nurse->delete()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
