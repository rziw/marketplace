<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class HomeController extends Controller
{
    private $request;
    private $productRepository;

    public function __construct(Request $request, ProductRepository $productRepository)
    {
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                $products = $this->listForAuthenticatedUser($user);
            } else {//token is invalid for any reason
                $products = $this->listForNotAuthenticatedUser();
            }

        } catch (JWTException $e) {//token not set
            $products = $this->listForNotAuthenticatedUser();
        }

        return response()->json(compact('products'));
    }

    public function listForNotAuthenticatedUser()
    {
        if ($this->request->has('lat') && !is_null($this->request->lat)
            && $this->request->has('lng') && !is_null($this->request->lng)
            && $this->request->has('radius') && !is_null($this->request->radius)) {

            $products = $this->productRepository->listClosest($this->request->lat, $this->request->lng, $this->request->radius);

        } else {
            $products = $this->productRepository->list();
        }

        return $products;
    }

    public function listForAuthenticatedUser( $user)
    {
        if (!is_null($user->latitude) && !is_null($user->longitude)) {

            $products = $this->productRepository->listClosest($user->latitude, $user->longitude, $user->radius);

        } elseif ($this->request->has('lat') && !is_null($this->request->lat)
            && $this->request->has('lng') && !is_null($this->request->lng)
            && $this->request->has('radius') && !is_null($this->request->radius)
        ) {

            $products = $this->productRepository->listClosest($this->request->lat, $this->request->lng, $this->request->radius);

        } else {
            $products = $this->productRepository->list();
        }

        return $products;
    }
}
