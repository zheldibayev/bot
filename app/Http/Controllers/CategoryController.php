<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function create()
    {
        $categories = Category::get()->toTree();
        return view('category', compact('categories'));
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->title = $request->title;
        $category->parent_id = $request->parent_id;

        //dd($category);
        $category->save();

    }
}
