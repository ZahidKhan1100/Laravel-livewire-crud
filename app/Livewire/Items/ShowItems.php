<?php

namespace App\Livewire\Items;

use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class ShowItems extends Component
{
    // public $items;
    // public $categories;
    public $search;
    public $searchCategories = [];
    public $cartItems = [];

    use WithPagination;



    public function addToCart($itemId)
    {
        $item = Item::find($itemId);

        $session = session()->get('cart');

        if ($session) {
            $this->cartItems = $session;
        }
        
        $found = false;
        if ($this->cartItems) {
            foreach ($this->cartItems as &$cartItem) {
                if ($cartItem['id'] === $item->id) {
                    $cartItem['quantity'] += 1;
                    $cartItem['price'] = $item->price;
                    $cartItem['name'] = $item->name;
                    $cartItem['total'] = $cartItem['quantity'] * $cartItem['price'];
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            $this->cartItems[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1,
                'total' => $item->price,
            ];
        }

        session()->remove('cart');
        session()->put('cart', $this->cartItems);
        $cartCount = count($this->cartItems);

        $this->dispatch('cart-updated', ['count' => $cartCount, 'cartItems' => $this->cartItems]);
    }

    public function filterItems()
    {
        $this->resetPage();
    }

    public function searchItems()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchCategories = [];
    }

    public function render()
    {
        // Build the query dynamically based on search term and selected categories
        $query = Item::query();

        // Apply the search filter if it's set
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Apply the category filter if it's set
        if ($this->searchCategories) {
            $query->whereIn('category_id', $this->searchCategories);
        }

        // Get the filtered items with pagination
        $items = $query->with('category')->paginate(6);

        // Fetch active categories for the filter sidebar
        $categories = Category::where('status', 'active')->get();

        return view('livewire.items.show-items', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }
}
