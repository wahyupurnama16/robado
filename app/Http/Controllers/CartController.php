<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = [];
        if ($user) {
            $cartItems = Cart::with('produk')->where('id_user', $user->id)->get()->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->produk->namaProduk,
                    'price' => $data->produk->hargaProduk,
                    'qty' => $data->jumlahPemesanan,
                ];
            });
        }

        return view('cart/cart', compact('cartItems'));

    }

    public function getCart(Request $request, $id)
    {
        $cartItems = Cart::where('id_user', $id)->with('produk')->get(); // Pastikan relasi sudah benar
        return response()->json($cartItems);
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'qty' => 'required|integer',
        ]);
        $cartItem = Cart::where('id', $validated['id'])->first();
        if ($cartItem) {
            $cartItem->jumlahPemesanan = $validated['qty'];
            $cartItem->save();
        }

        return response()->json(['message' => $cartItem]);

    }

    public function createCart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'id_user' => 'required|string',
            'qty' => 'required|integer',
        ]);
        if ($request->qty <= 0) {
            $cart = Cart::where(['id_produk' => $validated['id'], 'id_user' => $validated['id_user']])->delete();
        } else {

            $cart = Cart::where(['id_produk' => $validated['id'], 'id_user' => $validated['id_user']])->first();
            if ($cart) {
                $cart->jumlahPemesanan = $validated['qty'];
                $cart->save();
            } else {
                Cart::updateOrCreate([
                    'id_produk' => $validated['id'],
                    'id_user' => $validated['id_user'],
                    'jumlahPemesanan' => $validated['qty'],
                ]);
            }
        }

        return response()->json(['message' => 'success']);

    }

    public function syncCart(Request $request)
    {
        $cart = $request->input('cart');
        $userId = Auth::id(); // Mengambil ID pengguna yang sedang login

        foreach ($cart as $item) {
            // Cek apakah item sudah ada di database untuk user ini
            $existingCartItem = Cart::where('id_user', $userId)->where('id_produk', $item['id'])->first();

            if ($existingCartItem) {
                // Jika ada, perbarui qty
                $existingCartItem->jumlahPemesanan = $item['qty'];
                $existingCartItem->save();
            } else {
                // Jika tidak ada, buat item baru di database
                Cart::create([
                    'id_user' => $userId,
                    'id_produk' => $item['id'],
                    'jumlahPemesanan' => $item['qty'],
                ]);
            }
        }

        return response()->json(['status' => 'Cart synced successfully']);

    }
}
