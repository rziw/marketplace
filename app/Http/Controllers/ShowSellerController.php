<?php

namespace App\Http\Controllers;

use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use  JWTAuth;

class ShowSellerController extends Controller
{
    private $request;
    private $sellerRepository;

    public function __construct(Request $request, SellerRepository $sellerRepository)
    {
        $this->request = $request;
        $this->sellerRepository = $sellerRepository;
    }

    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                $products = $this->showForAuthenticatedUser($user, $id);
            } else {//token is invalid for any reason
                $products = $this->showForNotAuthenticatedUser($id);
            }

        } catch (JWTException $e) {//token not set
            $products = $this->showForNotAuthenticatedUser($id);
        }

        return response()->json(compact('products'));
    }

    public function showForNotAuthenticatedUser($id)
    {
        if ($this->request->has('lat') && !is_null($this->request->lat)
            && $this->request->has('lng') && !is_null($this->request->lng)
            && $this->request->has('radius') && !is_null($this->request->radius)) {

            $products = $this->sellerRepository->showClosest($id, $this->request->lat, $this->request->lng,
                $this->request->radius);

        } else {
            $products = $this->sellerRepository->show($id);
        }

        return $products;
    }

    public function showForAuthenticatedUser($user, $id)
    {
        if (!is_null($user->latitude) && !is_null($user->longitude)) {

            $products = $this->sellerRepository->showClosest($id, $user->latitude, $user->longitude,
                $user->radius);

        } elseif ($this->request->has('lat') && !is_null($this->request->lat)
            && $this->request->has('lng') && !is_null($this->request->lng)
            && $this->request->has('radius') && !is_null($this->request->radius)
        ) {

            $products = $this->sellerRepository->showClosest($id, $this->request->lat, $this->request->lng,
                $this->request->radius);

        } else {
            $products = $this->sellerRepository->show($id);
        }

        return $products;
    }
}
