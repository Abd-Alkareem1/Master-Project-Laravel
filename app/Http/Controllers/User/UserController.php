<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function home() {
        $pizzas = Product::orderBy('created_at', 'desc')->get();
        $categories = Category::get();
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $orders = Order::where('user_id', Auth::user()->id)->get();
        return view('user.main.home', compact('pizzas', 'categories', 'carts', 'orders'));
    }

    public function carts() {
        $cartList = Cart::all();
        $totalPrice = $this->getTotalPrice($cartList);
        return view('user.cart.carts', compact('cartList', 'totalPrice'));
    }

    public function history() {
        $orders = Order::orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate(4);
        return view('user.main.history', compact('orders'));
    }

    public function filter($id) {
        $pizzas = Product::where('category_id', $id)->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        $carts = Cart::get();
        $orders = Order::get();
        return view('user.main.home', compact('pizzas', 'categories', 'carts', 'orders'));
    }

    public function show($id) {
        Product::find($id)->increment('view_count');
        $pizza = Product::find($id);
        $pizzaList = Product::all();
        return view('user.main.show', compact('pizza', 'pizzaList'));
    }

    public function view() {
        return view('user.account.view');
    }

    public function edit($id) {
        return view('user.account.edit');
    }

    public function update(Request $request, $id) {
        $this->validateCreate($request);
        $data = $this->getData($request);
        
        if($request->hasFile('image')) {
            $user = User::where('id', $id)->first();
            $dbImage = $user->image;

            if($dbImage != null ) {
                Storage::delete('public/'.$dbImage);
            }

            $fileName = uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public', $fileName);
            $data['image'] = $fileName;
        }

        User::where('id', $id)->update($data);
        return redirect()->route('user#view')->with(['success' => 'Account Updated successfully']);
    }

    public function changePassword() {
        return view('user.account.changePassword');
    }

    public function updatePassword(Request $request) {
        $this->validatePassword($request);
        $user = User::select('password')->where('id', Auth::user()->id)->first();
        $dbPassword = $user->password;

        if(Hash::check($request->oldPassword, $dbPassword)) {
            $data = [
                'password' => Hash::make($request->newPassword)
            ];
            User::where('id', Auth::user()->id)->update($data);
            return back()->with(['success' => 'Password changed successfully...']);
        }
        return back()->with(['fail' => 'The old password does not match ']);
    }
    






    private function getTotalPrice($cartList) {
        $totalPrice = 0;
        foreach($cartList as $cart) {
            $totalPrice += $cart->product->price * $cart->quantity;
        }
        return $totalPrice;
    }

   private function validateCreate($request) {
        Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'image' => 'mimes:png,jpg,jpeg,web,webp|file',
            'gender' => 'nullable|string',
            'phone' => 'numeric|min:9|nullable',
            'address' => 'nullable|string',

        ], [])->validate();
   }

   private function getData($request) {
    return [
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'phone' => $request->phone,
        'address' => $request->address,
        'updated_at' => Carbon::now(),
    ];
   }


    private function validatePassword($request) {
        Validator::make($request->all(), [
            'oldPassword' => 'required|min:6',
            'newPassword' => 'required|min:6|same:confirmPassword',
            'confirmPassword' => 'required|min:6|same:newPassword',
        ],[])->validate();
    }
}
