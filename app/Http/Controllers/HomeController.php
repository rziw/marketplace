<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProductRepository;

class HomeController extends Controller
{
    private $request;
    private $productRepository;

    public function __construct(Request $request, ProductRepository $productRepository)
    {
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    public function index(): JsonResponse
    {
        $user = auth('api')->user();

        if ($user) {
            $products = $this->listForAuthenticatedUser($user);
        } else {//token is invalid for any reason
            $products = $this->listForUnAuthenticatedUser();
        }

        return response()->json(compact('products'));
    }

    public function listForUnAuthenticatedUser()
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

    public function listForAuthenticatedUser(User $user)
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
