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
        $itemExist = Item::find($itemId);
        $session = json_decode(Redis::get('cart'), true);

        $itemUpdated = false;

        if ($session) {
            $itemCollection = collect($session);
            $this->cartItems = $itemCollection->map(function ($item) use ($itemId, &$itemUpdated) {
                if ($item['id'] === $itemId) {
                    $itemUpdated = true;
                    $item['quantity'] += 1;
                    $item['id'] = $itemId;
                    $item['stock'] = $item['quantity'];
                    $item['name'] = $item['name'];
                    $item['price'] = $item['price'];
                    $item['total'] = $item['quantity'] * $item['price'];
                }
                return $item;
            })->toArray();
        }

        if (!$itemUpdated) {
            $this->cartItems[] = [
                'id' => $itemExist->id,
                'name' => $itemExist->name,
                'price' => $itemExist->price,
                'quantity' => 1,
                'stock' => $itemExist->quantity,
                'total' => $itemExist->price,
            ];
        }



        Redis::del('cart');
        Redis::set('cart', json_encode($this->cartItems));
        $this->cartItems = [];

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

        $query = Item::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->searchCategories) {
            $query->whereIn('category_id', $this->searchCategories);
        }

        $items = $query->with('category')->paginate(6);

        $categories = Category::where('status', 'active')->get();

        return view('livewire.items.show-items', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }
}
