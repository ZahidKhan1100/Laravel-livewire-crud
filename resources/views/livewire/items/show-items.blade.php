<div class="grid grid-cols-10 min-h-[90vh] gap-8 w-[80vw] mx-auto mb-20">
    <!-- Filter Sidebar -->
    <div class="col-span-3 backdrop:blur-lg min-h[70vh]">
        <div class="p-4">
            <label for="search">Search</label>
            <form wire:submit.prevent="searchItems">
                <input type="text" class="w-full p-2 border border-gray-300 rounded-md" wire:model="search">
                <button type="submit"
                    class="px-4 py-2 mt-2 text-white bg-blue-400 rounded-md hover:bg-blue-500">Search</button>
            </form>
        </div>

        <!-- Categories Filter -->
        <div class="flex flex-col p-4 space-y-2">
            <h1>Categories</h1>
            <ul class="space-x-2 space-y-2 h-[50vh] overflow-y-auto">
                @foreach ($categories as $category)
                    <li class="flex items-center space-x-2">
                        <input type="checkbox" value="{{ $category->id }}" wire:model="searchCategories"
                            wire:change="filterItems">
                        <label for="category{{ $category->id }}">{{ $category->name }}</label>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Tag Filter (Example: Best Deals only) -->
        <div class="flex flex-col p-4 space-y-2">
            <ul class="space-x-2 space-y-2">
                <input type="checkbox" name="tag" id="tag1">
                <label for="tag1">Best Deals only</label>
            </ul>
        </div>

        <!-- Filter Buttons -->
        <div class="flex justify-between p-4">
            <button wire:click="filterItems" class="px-4 py-2 text-white bg-blue-400 rounded-md hover:bg-blue-500">Apply
                Filters</button>
            <button wire:click="clearSearch" class="px-4 py-2 text-white bg-red-400 rounded-md hover:bg-red-500">Clear
                Filters</button>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="col-span-7 space-y-4">
        <!-- Search Bar -->
        <div class="flex justify-between gap-2 p-4">
            <input type="text" readonly class="flex-1 col-span-3 p-2 text-2xl bg-gray-200 rounded" value="search"
                wire:model="search">
            <button wire:click="clearSearch" class="px-4 py-2 text-white bg-red-400 rounded-md hover:bg-red-500">Clear
                Search</button>
        </div>

    
        <!-- Items Display -->
        <div class="grid grid-cols-3 gap-2">
            @forelse ($items as $item)
                <div class="max-w-sm overflow-hidden bg-white border border-gray-200 rounded-lg shadow-xl">
                    <img class="object-cover w-full h-40" src="{{ $item->image }}" alt="Product Image">
                    <div class="flex flex-col justify-center p-4 ">
                        <h2 class="text-lg font-bold text-gray-900">{{ $item->name }}</h2>
                        <p class="mt-2 text-sm text-gray-700 ">{{ Str::limit($item->description, 60, '...') }}</p>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xl font-semibold text-gray-900">${{ $item->price }}</span>
                            <button class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600" wire:click="addToCart({{ $item->id }})">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

            @empty
                <div class="flex items-center justify-center col-span-3">
                    <h1>No items found</h1>
                </div>
            @endforelse
            
        </div>
        <div>
            {{ $items->links() }}
        </div>
    </div>
</div>
