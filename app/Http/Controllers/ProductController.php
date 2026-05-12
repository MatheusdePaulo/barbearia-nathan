<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductSale;
use App\Models\Transaction;
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

        // 1. Baixa no Estoque
        $product->decrement('stock', $quantidade);

        // 2. Registra a Venda no histórico de vendas de produtos
        ProductSale::create([
            'product_id' => $product->id,
            'quantity' => $quantidade,
            'total_price' => $product->price * $quantidade,
            'date' => now(),
        ]);

        // 3. Alimenta a Tabela de Transações com a Categoria CORRETA para o Gráfico
        Transaction::create([
            'description' => "Venda: {$product->name} (x{$quantidade})",
            'amount' => $product->price * $quantidade,
            'type' => 'income',
            'category' => 'product', // CRUCIAL: Faz a barra de "Produtos" do gráfico subir
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Venda realizada e faturamento atualizado!');
    }
}
