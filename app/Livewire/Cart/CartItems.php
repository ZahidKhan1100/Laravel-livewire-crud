<?php

namespace App\Livewire\Cart;

use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\On;
use Livewire\Component;

class CartItems extends Component
{
    public $cartItems;

    protected $listeners = ['cartUpdated'];

    public function mount()
    {
        // $this->cartItems = session()->get('cart');
        $this->cartItems = json_decode(Redis::get('cart'), true);
    }

    public function clearCart()
    {
        $this->cartItems = [];
        // session()->remove('cart');
        Redis::del('cart');


        // session()->put('cart', $this->cartItems);
        Redis::set('cart', json_encode($this->cartItems));
        $this->dispatch('clearCount');
    }

    #[On('cartUpdated')]
    public function cartUpdated()
    {
        // $this->cartItems = session()->get('cart');
        $this->cartItems = json_decode(Redis::get('cart'), true);
        $this->dispatch('cartItemUpdated');

    }

    public function incrementQuantity($itemId)
    {
        // $cartItems = session()->get('cart', []);
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as &$cartItem) {
            if ($cartItem['id'] === $itemId) {
                $cartItem['quantity'] += 1;
                $cartItem['total'] = $cartItem['quantity'] * $cartItem['price'];
                break;
            }
        }

        // session()->remove('cart');
        Redis::del('cart');


        // session()->put('cart', $cartItems);
        Redis::set('cart', json_encode($cartItems));

        $this->cartItems = $cartItems;
        $this->dispatch('cartItemUpdated');

    }

    public function decrementQuantity($itemId)
    {
        // $cartItems = session()->get('cart');
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as &$cartItem) {
            if ($cartItem['id'] === $itemId) {
                $cartItem['quantity'] -= 1;
                $cartItem['total'] = $cartItem['quantity'] * $cartItem['price'];
                break;
            }
        }

        // session()->remove('cart');
        Redis::del('cart');

        // session()->put('cart', $cartItems);
        Redis::set('cart', json_encode($cartItems));

        $this->cartItems = $cartItems;
        $this->dispatch('cartItemUpdated');

    }

    public function render()
    {
        return view('livewire.cart.cart-items', ['cartItems' => $this->cartItems]);
    }
}
