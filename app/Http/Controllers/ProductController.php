<?php

namespace App\Http\Controllers;

use App\Model\Featured;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Model\Product;
use App\Model\ProductPhoto;
use App\Model\Category;

class ProductController extends Controller {
    public function home() {
        try {
            $filter = [
                'order_position' => true
            ];

            $featuredInstance = new Featured();
            $featureds = $featuredInstance->getFeaturedList($filter, false);

            return view('product.home', compact('featureds'));
        } catch (AppException $e) {
            return Redirect::route('website.contact')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function show() {
        return view('product.show');
    }

    public function index() {
        try {
            $filter = Session::get('productSearch');
            $productInstance = new Product();
            $categories = $productInstance->getProductList($filter, true);

            return view('product.index', compact('categories', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('productSearch', $filter);
            return Redirect::route('app.product.index');
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;
            $categoryInstance = new Category();
            $categories = $categoryInstance->getCategoryList(null, false);
            $productPhotos = [];

            return view('product.form', compact('modeEdit', 'categories', 'productPhotos'));
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $productInstance = new Product();
            $validateRequest = Helper::validateRequest($request, $productInstance->validationRules, $productInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $productInstance = new Product();
            $product = $productInstance->storeProduct($request);

            return Redirect::route('app.product.index')
                ->with('status', $product['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $productInstance = new Product();
            $product = $productInstance->getProductById($id);
            $productPhotoInstance = new ProductPhoto();
            $filter = [
                'product_id' => $id
            ];
            $productPhotos = $productPhotoInstance->getProductPhotoList($filter, false);
            $categoryInstance = new Category();
            $categories = $categoryInstance->getCategoryList(null, false);
            $modeEdit = true;

            return view('product.form', compact('product', 'modeEdit', 'categories', 'productPhotos'));
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $productInstance = new Product();
            $product = $productInstance->updateProduct($request, $id);

            return Redirect::back()
                ->with('status', $product['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function photoMain($productId, $photoId) {
        try {
            $productInstance = new Product();
            $product = $productInstance->setPhotoMain($productId, $photoId);

            return Redirect::back()
                ->with('status', $product['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function photoRemove($photoId) {
        try {
            $productPhotoInstance = new ProductPhoto();
            $productPhoto = $productPhotoInstance->deleteProductPhoto($photoId);

            return Redirect::back()
                ->with('status', $productPhoto['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $productInstance = new Product();
            $message = $productInstance->deleteProduct($id);

            return Redirect::route('app.product.index')
                ->with('status', $message);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
