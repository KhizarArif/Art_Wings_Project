<?php

namespace App\Http\Controllers;

use App\Http\Services\FrontendServices;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $frontendServices;

    public function __construct(FrontendServices $frontendServices)
    {
        $this->frontendServices = $frontendServices;
    }

    public function index()
    {
        return $this->frontendServices->index();
    }
    public function cart()
    {
        return $this->frontendServices->cart();
    }

    public function productDetails(Request $request, $subcategorySlug = null, $productSlug = null)
    {
        return $this->frontendServices->productDetails($request, $subcategorySlug, $productSlug);
    }

    public function addToCart(Request $request)
    {
        return $this->frontendServices->addToCart($request);
    }

    public function shoppingCarts()
    {
        return $this->frontendServices->shoppingCarts();
    }
    public function checkouts()
    {
        return $this->frontendServices->checkouts();
    }

    public function deleteToCart(Request $request)
    {
        return $this->frontendServices->deleteToCart($request);
    }
    public function updateCart(Request $request)
    {
        return $this->frontendServices->updateCart($request);
    }
    public function getShippingAmount(Request $request)
    {
        return $this->frontendServices->getShippingAmount($request);
    }
    public function processCheckout(Request $request)
    {
        return $this->frontendServices->processCheckout($request);
    }
    public function thankyou(Request $request)
    {
        return $this->frontendServices->thankyou($request);
    }
    public function subCategoryProducts($subcategoryId)
    {
        return $this->frontendServices->subCategoryProducts($subcategoryId);
    }

    public function subProducts($subcategorySlug = null)
    {
        return $this->frontendServices->subProducts($subcategorySlug);
    }

    public function filterCategories(Request $request)
    {
        return $this->frontendServices->filterCategories($request);
    }
    public function getInitialCategory()
    {
        return $this->frontendServices->getInitialCategory();
    }
}
