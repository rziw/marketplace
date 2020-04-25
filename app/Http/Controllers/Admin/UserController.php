<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\GeoLocationHandler;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::get();
        return response()->json(compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $input = $request->all();

        if(isset($request->address)) {
            $geoLocation = new GeoLocationHandler($user, $request);

            $input['longitude'] = $geoLocation->getLongitude();
            $input['latitude'] = $geoLocation->getLatitude();
        }

        $user->update($input);
        $this->checkForCreateShop($user, $request);

        return response()->json(['message'=> 'You have successfully updated the user.']);
    }

    private function checkForCreateShop($user, $request) { //TODO should it be in a separate class??
        if(isset($request->role) && $request->role == 'seller') {
            app()->call('App\Helpers\ShopCreation@store', [$user]);
        }
    }
}
