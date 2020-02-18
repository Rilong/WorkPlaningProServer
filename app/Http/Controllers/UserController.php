<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * test ssh
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'The user not found'], 400);
        }
        return response()->json($user->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $user->fill($request->except(['password', 'email']));
        $user->save();
        return response()->json(['message' => 'The user changed.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = auth()->user();
        $user->remove();
        return response()->json(['message' => 'The user deleted.'], 200);
    }
}
