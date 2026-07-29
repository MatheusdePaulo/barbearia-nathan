<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        // Mantemos a contagem de aniversariantes para o menu lateral
        $hoje = now()->format('m-d');
        $aniversariantesHoje = User::whereRaw("strftime('%m-%d', birthday) = ?", [$hoje])->count();

        return view('admin.products.index', compact('products', 'aniversariantesHoje'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable' // Importante caso você adicione upload depois
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produto cadastrado!');
    }

    /**
     * Atualiza apenas Preço e Estoque (Blindagem de Design)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);

        // Nathan só altera o financeiro e o estoque para não quebrar sua estética
        $product->update([
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // Redireciona com mensagem de sucesso para o Toast do Admin
        return redirect()->route('admin.products.index')->with('success', 'Valores atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produto removido!');
    }
}
