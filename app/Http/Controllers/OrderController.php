<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Method to get all orders
    public function index()
    {
        try {
            $orders = Order::with('products')->get();
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch orders'], 500);
        }
    }

    // Method to create a new order
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'sale_date' => 'required|date',
            'destination_country' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Create the order
            $order = Order::create($request->only(['sale_date', 'destination_country']));

            // Associate products with quantity to the order
            $products = collect($request->products)->mapWithKeys(function ($item) {
                return [$item['product_id'] => ['quantity' => $item['quantity']]];
            });

            $order->products()->attach($products);

            return response()->json($order->load('products'), 201);

        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }

    // Method to get a single order
    public function show($id)
    {
        try {
            $order = Order::with('products')->findOrFail($id);
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch order'], 500);
        }
    }

    // Method to update an existing order
    public function calculateSavedCO2(Request $request)
{
    try {
        $query = Order::query();

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('sale_date', [$request->input('start_date'), $request->input('end_date')]);
        }

        // Filter by destination country
        if ($request->has('destination_country')) {
            $query->where('destination_country', $request->input('destination_country'));
        }

        // Retrieve orders with their products
        $orders = $query->with('products')->get();

        // Calculate the total saved CO2
        $totalCO2 = $orders->sum(function ($order) use ($request) {
            return $order->products->sum(function ($product) use ($request) {
                // Check if 'product_id' filter is applied
                if ($request->has('product_id')) {
                    // Only include CO2 savings for the specified product_id
                    if ($product->id == $request->input('product_id')) {
                        return $product->pivot->quantity * $product->co2_saved;
                    } else {
                        return 0; // Ignore this product if it doesn't match the filter
                    }
                } else {
                    // No filter applied, include CO2 savings for all products
                    return $product->pivot->quantity * $product->co2_saved;
                }
            });
        });

        return response()->json(['co2_saved' => $totalCO2], 200);

    } catch (\Exception $e) {
        Log::error('Error calculating CO2 saved: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to calculate CO2 saved'], 500);
    }
}
}