<?php

namespace App\Livewire\Items;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\WithPagination;

class ShowItems extends Component
{
    public $search;
    public $searchCategories = [];
    public $cartItems = [];

    use WithPagination;



    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        $session = json_decode(Redis::get('cart'), true);




        if ($session) {
            $itemCollection = collect($session);
            $this->cartItems = $itemCollection->map(function ($item) use ($itemId) {
                if ($item['id'] === $itemId) {
                    $item['id'] = $itemId;
                    $item['name'] = $item['name'];
                    $item['price'] = $item['price'];
                    $item['quantity'] = $item['quantity'] + 1;
                    $item['total'] = $item['quantity'] * $item['price'];
                }
                return $item;
            })->toArray();
        } else {
            $this->cartItems[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1,
                'total' => $item->price,
            ];
        }


        Redis::del('cart');

        Redis::set('cart', json_encode($this->cartItems));

        $this->dispatch('cartUpdated');
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
