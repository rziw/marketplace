<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\GeoLocationHandler;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $geoLocation = new GeoLocationHandler($user, $request);

        $input = $request->except(['role']);
        $input['longitude'] = $geoLocation->getLongitude();
        $input['latitude'] = $geoLocation->getLatitude();

        $user->update($input);

        return response()->json(['message' => 'You have successfully updated the user.']);
    }
}
