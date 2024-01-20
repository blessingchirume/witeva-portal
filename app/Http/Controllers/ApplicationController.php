<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function order(Request $request)
    {
        $data = $request->validate([]);

        $order = new Order();

        // return response($request);

        $order->create([
            'order_number' => $request->order_number,
            'order_ref_number' => $request->order_ref_number,
            'payment_status' => $request->payment_status,
            'customer_delivery_status' => $request->customer_delivery_status,
            'admin_delivery_status' => $request->admin_delivery_status,
            'delivery_date' => $request->delivery_date,
            'approval_status' => $request->approval_status,
            'user_id' => 1

        ]);


        foreach ($request->order_items as $row) {
            $order->items()->attach(Item::where('id', $row['id'])->first());
        }
    }

    public function orders()
    {
        $orders = Order::with('items')->get();

        return response($orders);
    }

    public function items() 
    {
        $items = Item::all();
        return response()->json(['success' => $items, 'error' => null ]);
    }
}
