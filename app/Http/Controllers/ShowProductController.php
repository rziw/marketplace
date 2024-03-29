<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use Tymon\JWTAuth\Exceptions\JWTException;

class ShowProductController extends Controller
{
    private $request;
    private $productRepository;

    public function __construct(Request $request, ProductRepository $productRepository)
    {
        $this->request = $request;
        $this->productRepository = $productRepository;
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

            $products = $this->productRepository->getClosest($id, $this->request->lat, $this->request->lng,
                $this->request->radius);

        } else {
            $products = $this->productRepository->find($id);
        }

        return $products;
    }

    public function showForAuthenticatedUser($user, $id)
    {
        if (!is_null($user->latitude) && !is_null($user->longitude)) {

            $products = $this->productRepository->getClosest($id, $user->latitude, $user->longitude,
                $user->radius);

        } elseif ($this->request->has('lat') && !is_null($this->request->lat)
            && $this->request->has('lng') && !is_null($this->request->lng)
            && $this->request->has('radius') && !is_null($this->request->radius)
        ) {

            $products = $this->productRepository->getClosest($id, $this->request->lat, $this->request->lng,
                $this->request->radius);

        } else {
            $products = $this->productRepository->find($id);
        }

        return $products;
    }
}
