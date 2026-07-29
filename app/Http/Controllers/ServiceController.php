<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Review;
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $services = Service::all();
        $products = Product::all();

        $reviewAverage = Review::whereIn('rating', [3, 4, 5])->avg('rating');
        $reviewAverage = $reviewAverage ? number_format($reviewAverage, 1) : null;
        $reviewTotal   = Review::count();

        return view('welcome', compact('services', 'products', 'reviewAverage', 'reviewTotal'));
=======
        // Busca os serviços e produtos para a vitrine
        $services = Service::all();
        $products = Product::all();

        // Carrega a view 'welcome' que tem o seu design do Figma
        return view('welcome', compact('services', 'products'));
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    }
}
