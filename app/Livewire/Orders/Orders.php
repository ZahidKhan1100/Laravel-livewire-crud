<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;

class Orders extends Component
{
    public $orders;

    public function mount()
    {
        $this->orders = Order::all();
    }

    // Update the status of an order
    public function updateStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        $order->status = $status;
        $order->save();
        $this->orders = Order::all();
    }

    // Update the notes of an order
    public function updateNotes($orderId, $notes)
    {
        $order = Order::find($orderId);
        $order->notes = $notes;
        $order->save();
        $this->orders = Order::all();
    }

    // Delete an order
    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        $order->delete();
        $this->orders = Order::all();
    }

    public function render()
    {
        return view('livewire.orders.orders');
    }
}
