<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        // Busca os serviços e produtos para a vitrine
        $services = Service::all();
        $products = Product::all();

        // Carrega a view 'welcome' que tem o seu design do Figma
        return view('welcome', compact('services', 'products'));
    }
}
