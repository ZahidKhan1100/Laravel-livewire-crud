<?php

namespace App\Livewire\Cart;

use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\On;
use Livewire\Component;

class CartItems extends Component
{
    public $cartItems;

    protected $listeners = ['cartUpdated'];


    public function clearCart()
    {
        Redis::del('cart');
        $this->cartItems = [];
        $this->dispatch('clearCount');
    }

    #[On('cartUpdated')]
    public function cartUpdated()
    {
        $cart = Redis::get('cart');
        $this->cartItems = $cart ? json_decode($cart, true) : [];
    }

    public function incrementQuantity($itemId)
    {
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as &$cartItem) {
            if ($cartItem['id'] === $itemId) {
                $cartItem['quantity'] += 1;
                $cartItem['total'] = $cartItem['quantity'] * $cartItem['price'];
                break;
            }
        }

        Redis::del('cart');
        Redis::set('cart', json_encode($cartItems));

        $this->cartItems = $cartItems;
        $this->dispatch('cartUpdated');
    }

    public function decrementQuantity($itemId)
    {
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as &$cartItem) {
            if ($cartItem['id'] === $itemId) {
                if ($cartItem['quantity'] === 1) {
                    $this->removeItem($itemId);
                    return;
                }
                $cartItem['quantity'] -= 1;
                $cartItem['total'] = $cartItem['quantity'] * $cartItem['price'];
                break;
            }
        }

        Redis::del('cart');

        Redis::set('cart', json_encode($cartItems));

        $this->cartItems = $cartItems;
        $this->dispatch('cartUpdated');
    }

    public function removeItem($itemId)
    {
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['id'] === $itemId) {
                unset($cartItems[$key]);
                break;
            }
        }

        Redis::del('cart');
        Redis::set('cart', json_encode($cartItems));

        $this->cartItems = $cartItems;
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $this->cartItems = json_decode(Redis::get('cart'), true);
        return view('livewire.cart.cart-items', ['cartItems' => $this->cartItems]);
    }
}
