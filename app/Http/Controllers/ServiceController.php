<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $products = Product::all();

        $reviewAverage = Review::whereIn('rating', [3, 4, 5])->avg('rating');
        $reviewAverage = $reviewAverage ? number_format($reviewAverage, 1) : null;
        $reviewTotal   = Review::count();

        return view('welcome', compact('services', 'products', 'reviewAverage', 'reviewTotal'));
    }
}
