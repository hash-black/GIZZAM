<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::with('productDetail')->latest()->get();

        $customers = Customer::with('orders')->latest()->get();

        $orders = Order::with(['customer', 'products'])->latest()->get();

        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalSales = Order::where('status', 'Completed')
            ->sum('total_amount');

        return view('dashboard', compact(
            'products',
            'customers',
            'orders',
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'totalSales'
        ));
    }
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'brand' => 'required|string|max:255',
            'warranty' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            $product = Product::create([
                'product_name' => $validated['product_name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
            ]);

            $product->productDetail()->create([
                'brand' => $validated['brand'],
                'warranty' => $validated['warranty'],
                'description' => $validated['description'] ?? null,
            ]);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Product added successfully!');
    }
    public function storeCustomer(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'nullable|string|max:30',
    ]);

    Customer::create($validated);

    return redirect()->route('dashboard')
        ->with('success', 'Customer registered successfully!');
}
public function storeOrder(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'products' => 'required|array|min:1',
        'products.*.id' => 'required|distinct|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    DB::transaction(function () use ($validated) {

        $total = 0;
        $orderProducts = [];

        foreach ($validated['products'] as $item) {

            $product = Product::whereKey($item['id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($product->stock < $item['quantity']) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'products' => "Not enough stock for {$product->product_name}.",
                ]);
            }

            $price = $product->price;
            $quantity = $item['quantity'];

            $total += $price * $quantity;

            $orderProducts[$product->id] = [
                'quantity' => $quantity,
                'price' => $price,
            ];

            $product->decrement('stock', $quantity);
        }

        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'total_amount' => $total,
            'status' => 'Completed',
        ]);

        $order->products()->attach($orderProducts);
    });

    return redirect()->route('dashboard')
        ->with('success', 'Purchase recorded successfully!');
}
}
