<?php

namespace App\Http\Controllers\StaffBaristas;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\MenuItems;

class MenuController extends Controller
{
    public function index()
{
    $categories = Categories::all();
    return view('staff_baristas.menu.index')
    ->with('categories', $categories);
}

public function toggleAvailability(Request $request, $id)
{
    $item = MenuItems::findOrFail($id);
    $item->is_available = !$item->is_available;
    $item->save();

    return response()->json(['success' => true, 'status' => $item->is_available]);
}

}