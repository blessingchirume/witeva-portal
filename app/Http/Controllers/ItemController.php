<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('modules.items.index', compact('items'));
    }

    public function create()
    {
        return view('modules.items.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'item_description' => 'required',
            'price' => 'required',
        ]);
        try {
            $user = new Item();
            $user->create([
                'item_code' => rand(000000, 999999),
                'item_description' => $request->item_description,
                'price' => $request->price,
                'category' => 'sea food',
                'image' => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.target.com%2Fp%2Fextra-large-red-seedless-grapes-1-5lb-bag%2F-%2FA-15013626&psig=AOvVaw1NO4zgJz-A_M9kpvrADf3_&ust=1705154225631000&source=images&cd=vfe&opi=89978449&ved=0CBMQjRxqFwoTCIihpNGA2IMDFQAAAAAdAAAAABAQ'
            ]);
            return redirect()->route('user.index')->with('success', 'product item created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
