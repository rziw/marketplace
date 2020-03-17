<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\CreateShop;

class ResourceController extends Controller
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
        $user->update($input);
        $this->checkForCreateShop($user, $request);

        return response()->json(['message'=> 'You have successfully updated the user.']);
    }

    private function checkForCreateShop($user, $request) {
        if(isset($request->role) && $request->role == 'seller') {
            $shop = new CreateShop();
            $shop->store($user);
        }
    }
}
