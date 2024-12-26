<?php

namespace App\Http\Controllers;

use App\Models\VpFavouriteProduct;
use App\Models\VpOrder;
use App\Models\VpProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Cart;

class CartController extends Controller
{
    public function getAddCart($id)
    {
        $product = VpProduct::find($id);
        Cart::add([
            'id' => $id,
            'name' => $product->prod_name,
            'price' => $product->prod_price,
            'quantity' => 1,
            'attributes' => [
                'img' => $product->prod_img,
                'added_at' => time()
            ]
        ]);

        return redirect('cart/show');
    }
    public function getShowCart()
    {
        $products = Cart::getContent()->sortBy(function($cart) {
            return $cart->attributes->added_at ?? 0;
        });
        $total = Cart::getTotal();
        return view('frontend.cart', compact('products', 'total'));
    }
    public function getDeleteCart($id)
    {
        if($id == 'all') {
            Cart::clear();
        } else {
            Cart::remove($id);
        }

        return back();
    }
    public function getUpdateCart(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;
        
        $currentItem = Cart::get($id);
        
        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'attributes' => [
                'img' => $currentItem->attributes->img,
                'added_at' => $currentItem->attributes->added_at ?? time()
            ]
        ]);
        
        return response()->json(['success' => true]);
    }
    public function postPayCart(Request $request)
    {
        $order = new VpOrder;
        $order->name = $request->name;
        $order->address = $request->add;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->total_price = Cart::getTotal();
        $products = Cart::getContent()->sortBy(function($cart) {
            return $cart->attributes->added_at ?? 0;
        });
        $order->total_products = $products->pluck('name')->implode('; ');
        $order->placed_order_date = now()->format('d/m/Y');
        $order->user_id = Auth::id();
        $order->save();

        $data['info'] = $request->all();
        $email = $request->email;
        $name = $request->name;
        $data['cart'] = $products;
        $data['total'] = Cart::getTotal();
        Cart::clear();
        return redirect('complete');
    }
    public function getComplete()
    {
        return view('frontend.complete');
    }
    public function addFavourite(Request $request, $id)
    {
        $favourite_prod = new VpFavouriteProduct;
        $favourite_prod->user_id = Auth::id();
        $favourite_prod->favou_product = $id;

        $favourite_prod->save();
        return back()->with('success', 'Thêm sản phẩm vào danh sách yêu thích thành công!');
    }
}
