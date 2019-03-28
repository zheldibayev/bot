<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use Rariteth\LaravelCart\Entities\CartItem;

class ProductController extends Controller
{


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Darryldecode\Cart\Exceptions\InvalidItemException
     */
    public function index()
    {

        $cart = app('cart');
       // $cart->addItem([
       //     'product_id'  => 2,
       //     'unit_price'  => 1800,
       //     'quantity'    => 1

       // ]);
        //$cart->items()->where('product_id', 2)->first()->delete();

        //dd($items);

        //$carts = $cart->items()->where('product_id', '1')->get()->count();
       // $product = Product::where('category_id', '4')->get();

        //dd($product);
       // echo $carts;

        //foreach ($carts as $cart){
        //    print_r($cart->quantity);
       // }

       // dd(storage_path('framework/sessions'));

       //  \Cart::add(array(
         //   'id' => 456,
           // 'name' => 'Sample Item',
            //'price' => 67.99,
            //'quantity' => 4,
            //'attributes' => array()
        //));

        //$itemId = 456;

        //$carts = \Cart::get($itemId);

       // echo $carts['quantity'];

       // foreach($carts as $cart) {

       // }


       // $getCart = \Cart::
     //  dd(new Cart()->add(1,'asdasd',300,10,[]));

     // dd($cart);

        $categories = Category::all();

        return view('product', compact('categories'));
    }

    public function store(Request $request)
    {


        $image = $request->file('image');

        $input['image'] = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images');

        $image->move($destinationPath, $input['image']);

        $product = new Product();

        $product->name = $request->name;

        $product->description = $request->description;

        $product->image = $input['image'];

        $product->price = $request->price;

        $product->category_id = $request->category_id;

        $product->save();

        return back();
    }
}
