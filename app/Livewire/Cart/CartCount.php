<?php

namespace App\Livewire\Cart;

use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\Attributes\On;

class CartCount extends Component
{
    public $cartCount;

    protected $listeners = ['cartUpdated', 'clearCount'];

    public function mount()
    {
        // $cartItems = session()->get('cart', []);
        $cartItems = json_decode(Redis::get('cart'), true);

        if ($cartItems !== null) {
            foreach ($cartItems as $cartItem) {
                $this->cartCount += $cartItem['quantity'];
            }
        }
    }

    #[On('clearCount')]
    public function clearCount()
    {
        $this->cartCount = 0;
        Redis::del('cart');
    }

    #[On('cartUpdated')]
    public function cartUpdated()
    {
        $cartItems = json_decode(Redis::get('cart'), true);
        $this->cartCount = 0;
    
        if ($cartItems) {
            foreach ($cartItems as $cartItem) {
                $this->cartCount += $cartItem['quantity'];
            }
        }
    }

    public function render()
    {
        return view('livewire.cart.cart-count', ['cartCount' => $this->cartCount]);
    }
}
