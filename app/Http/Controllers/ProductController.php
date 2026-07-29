<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductSale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $hoje = now()->format('m-d');
        // CORREÇÃO: MySQL usa DATE_FORMAT em vez de strftime
        $aniversariantesHoje = User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$hoje])->count();

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
            'image' => 'nullable'
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

        $product->update([
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Valores atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produto removido!');
    }

    /**
     * Realiza a venda, baixa estoque e alimenta o financeiro categorizado
     */
    public function sell(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantidade = $request->input('quantity', 1);

        if ($product->stock < $quantidade) {
            return redirect()->back()->with('error', 'Estoque insuficiente!');
        }

        DB::transaction(function () use ($product, $quantidade) {
            $product->decrement('stock', $quantidade);

            ProductSale::create([
                'product_id' => $product->id,
                'quantity' => $quantidade,
                'total_price' => $product->price * $quantidade,
                'date' => now(),
            ]);

            Transaction::create([
                'description' => "Venda: {$product->name} (x{$quantidade})",
                'amount' => $product->price * $quantidade,
                'type' => 'income',
                'category' => 'product',
                'date' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Venda realizada e faturamento atualizado!');
    }
}
