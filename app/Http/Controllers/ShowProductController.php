<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

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

            $products = $this->productRepository->showClosest($id, $this->request->lat, $this->request->lng,
                $this->request->radius);

        } else {
            $products = $this->productRepository->show($id);
        }

        return $products;
    }

    public function showForAuthenticatedUser($user, $id)
    {
        if (!is_null($user->latitude) && !is_null($user->longitude)) {

            $products = $this->productRepository->showClosest($id, $user->latitude, $user->longitude,
                $user->radius);

        } elseif ($this->request->has('lat') && !is_null($this->request->lat)
            && $this->request->has('lng') && !is_null($this->request->lng)
            && $this->request->has('radius') && !is_null($this->request->radius)
        ) {

            $products = $this->productRepository->showClosest($id, $this->request->lat, $this->request->lng,
                $this->request->radius);

        } else {
            $products = $this->productRepository->show($id);
        }

        return $products;
    }
}
