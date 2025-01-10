<?php

namespace App\Livewire\Checkout;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class Checkout extends Component
{

    public $items;
    public $customer_name;
    public $customer_email;
    public $customer_address;

    public function mount()
    {
        $this->items = json_decode(Redis::get('cart'), true);
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

        $this->items = $cartItems;
        $this->dispatch('cartUpdated');
    }

    public function incrementQuantity($itemId)
    {
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as &$cartItem) {
            if ($cartItem['id'] === $itemId) {
                $cartItem['quantity'] += 1;
                if ($cartItem['quantity'] > $cartItem['stock']) {
                    $cartItem['quantity'] = $cartItem['stock'];
                    $this->dispatch('showErrorToaster', 'The maximum stock limit has been reached.', 'error');
                }
                $cartItem['total'] = $cartItem['quantity'] * $cartItem['price'];
                break;
            }
        }

        Redis::del('cart');
        Redis::set('cart', json_encode($cartItems));

        $this->items = $cartItems;
        $this->dispatch('cartUpdated');
    }

    public function removeItem($itemId)
    {
        // $cartItems = session()->get('cart', []);
        $cartItems = json_decode(Redis::get('cart'), true);

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['id'] === $itemId) {
                unset($cartItems[$key]);
                $this->dispatch('showSuccessToaster', 'Item removed successfully.', 'success');
                break;
            }
        }

        Redis::del('cart');
        Redis::set('cart', json_encode($cartItems));

        $this->items = $cartItems;
        $this->dispatch('cartUpdated');
    }

    public function submitOrder()
    {

        $this->validate([
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_address' => 'required',
        ]);

        $cartItems = json_decode(Redis::get('cart'), true);

        $password = "password";

        $user = User::where('email', $this->customer_email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'password' => bcrypt($password),
            ]);
        }


        $order = [
            'order_number' => 'ORD-' . time(),
            'total' => array_sum(array_column($cartItems, 'total')),
            'status' => 'pending',
            'notes' => '',
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_address' => $this->customer_address,
            'user_id' => $user->id,
            'items' => $cartItems,
        ];

        Order::create($order);

        Redis::del('cart');
        $this->items = [];
        $this->customer_name = '';
        $this->customer_email = '';
        $this->customer_address = '';

        $this->dispatch('showSuccessToaster', 'Order submitted successfully.', 'success');
    }

    public function render()
    {
        return view('livewire.checkout.checkout');
    }
}
