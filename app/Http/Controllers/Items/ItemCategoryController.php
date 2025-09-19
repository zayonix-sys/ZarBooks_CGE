<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\Items\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemCategoryController extends Controller
{
    public function index(): View
    {
        $parentCategories = ItemCategory::whereNull('parent_id')->get();

        $categories = ItemCategory::whereParentId(null)->with('children')->get();
        //$categories = ItemCategory::with('children')->root()->get(['title', 'status']);

//        echo "<pre>";
//        print_r($categories);
        return view('items.items-category', compact('parentCategories', 'categories'));
    }

    public function storeParentCategory(Request $request)
    {
        $request->validate([
           'title' => 'required|string|max:50'
        ]);

        ItemCategory::updateOrCreate(['title' => $request->input('title')]);

        notify()->success('Parent Category added successfully', 'Category Created');
        return redirect('/items/itemsCategory');
    }

    public function storeItemCategory(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|numeric',
            'title' => 'required|string|max:50'
        ]);

        ItemCategory::updateOrCreate(
            [
                'parent_id' => $request->input('parent_id'),
                'title' => $request->input('title'),
            ]
        );

        notify()->success('Item Category added successfully', 'Item Category Created');
        return redirect('/items/itemsCategory');
    }

    public function updateItemCategory(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $itemCategory = ItemCategory::findOrFail($id);
        $itemCategory->title = $request->input('title');
        $itemCategory->status = $request->input('is_active');

        $itemCategory->save();

        notify()->success('Item Category updated successfully', 'Item Category Updated');
        return redirect('/items/itemsCategory');
    }
}
