<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\GeoLocationHandler;

class UserController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $geoLocation = new GeoLocationHandler($user, $request);

        $input = $request->except(['role']);
        $input['longitude'] = $geoLocation->getLongitude();
        $input['latitude'] = $geoLocation->getLatitude();

        $user->update($input);

        return response()->json(['message' => 'You have successfully updated the user.']);
    }

}
