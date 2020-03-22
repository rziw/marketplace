<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param ProductRepository $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ProductRepository $productRepository)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                $products = $this->listForAuthenticatedUser($request, $productRepository, $user);
            } else {//token is invalid for any reason
                $products = $this->listForNotAuthenticatedUser($request, $productRepository);
            }

        } catch (JWTException $e) {//token not set
            $products = $this->listForNotAuthenticatedUser($request, $productRepository);
        }

        return response()->json(compact('products'));
    }

    public function listForNotAuthenticatedUser($request, $productRepository)
    {
        if ($request->has('lat') && !is_null($request->lat)
            && $request->has('lng') && !is_null($request->lng)
            && $request->has('radius') && !is_null($request->radius)) {

            $products = $productRepository->listClosest($request->lat, $request->lng, $request->radius);

        } else {
            $products = $productRepository->list();
        }

        return $products;
    }

    public function listForAuthenticatedUser($request, $productRepository, $user)
    {
        if (!is_null($user->latitude) && !is_null($user->longitude)) {

            $products = $productRepository->listClosest($user->latitude, $user->longitude, $user->radius);

        } elseif ($request->has('lat') && !is_null($request->lat)
            && $request->has('lng') && !is_null($request->lng)
            && $request->has('radius') && !is_null($request->radius)
        ) {

            $products = $productRepository->listClosest($request->lat, $request->lng, $request->radius);

        } else {
            $products = $productRepository->list();
        }

        return $products;
    }
}
