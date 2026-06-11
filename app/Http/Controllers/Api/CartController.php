<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductItem;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/cart → ambil isi keranjang user
    public function index(Request $request)
    {
        $userId = $request->user()->idUser;

        $cart = Cart::firstOrCreate(
            ['idUser' => $userId],
            ['idCart' => $this->idGenerator->generate('CRT', Cart::class, 'idCart')]
        );

        $items = CartItem::where('idCart', $cart->idCart)
            ->with(['productItem.product', 'productItem.productVariations'])
            ->get();

        return response()->json([
            'idCart' => $cart->idCart,
            'items'  => $items,
        ]);
    }

    // POST /api/cart/items → tambah item ke keranjang
    public function addItem(Request $request)
    {
        $request->validate([
            'idItem'   => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = $request->user()->idUser;

        $cart = Cart::firstOrCreate(
            ['idUser' => $userId],
            ['idCart' => $this->idGenerator->generate('CRT', Cart::class, 'idCart')]
        );

        // Cek apakah item sudah ada di keranjang
        $existing = CartItem::where('idCart', $cart->idCart)
            ->where('idItem', $request->idItem)
            ->first();

        if ($existing) {
            // Tambah quantity
            $existing->update([
                'quantity' => $existing->quantity + $request->quantity,
            ]);
            $cartItem = $existing;
        } else {
            // Buat baru
            $cartItem = CartItem::create([
                'idCartItem' => $this->idGenerator->generate('CIT', CartItem::class, 'idCartItem'),
                'idCart'     => $cart->idCart,
                'idItem'     => $request->idItem,
                'quantity'   => $request->quantity,
                'isChecked'  => true,
                'createAt'   => now(),
            ]);
        }

        return response()->json([
            'message'  => 'Item berhasil ditambahkan ke keranjang',
            'cartItem' => $cartItem,
        ], 201);
    }

    // PUT /api/cart/items/{id}/quantity → update quantity
    public function updateQuantity(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('idCartItem', $id)
            ->whereHas('cart', fn($q) => $q->where('idUser', $request->user()->idUser))
            ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'message'  => 'Quantity berhasil diupdate',
            'cartItem' => $cartItem,
        ]);
    }

    // PUT /api/cart/items/{id}/checked → update isChecked
    public function updateChecked(Request $request, string $id)
    {
        $request->validate([
            'isChecked' => 'required|boolean',
        ]);

        $cartItem = CartItem::where('idCartItem', $id)
            ->whereHas('cart', fn($q) => $q->where('idUser', $request->user()->idUser))
            ->firstOrFail();

        $cartItem->update(['isChecked' => $request->isChecked]);

        return response()->json([
            'message'  => 'Status berhasil diupdate',
            'cartItem' => $cartItem,
        ]);
    }

    // PUT /api/cart/check-all → update semua isChecked
    public function updateAllChecked(Request $request)
    {
        $request->validate([
            'isChecked' => 'required|boolean',
        ]);

        $cart = Cart::where('idUser', $request->user()->idUser)->firstOrFail();

        CartItem::where('idCart', $cart->idCart)
            ->update(['isChecked' => $request->isChecked]);

        return response()->json(['message' => 'Semua item berhasil diupdate']);
    }

    // DELETE /api/cart/items/{id} → hapus item dari keranjang
    public function deleteItem(Request $request, string $id)
    {
        $cartItem = CartItem::where('idCartItem', $id)
            ->whereHas('cart', fn($q) => $q->where('idUser', $request->user()->idUser))
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang']);
    }
}