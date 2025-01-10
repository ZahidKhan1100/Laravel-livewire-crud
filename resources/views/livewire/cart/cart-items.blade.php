<div>
    <div class="flex items-center justify-between p-4 border-b border-slate-400">
        <h1 class="p-4 text-2xl font-bold text-center ">Cart</h1>
        <button class="px-4 py-2 text-white bg-red-400 rounded-md hover:bg-red-500" wire:click="clearCart">Clear
            Cart</button>
    </div>
    <div class="flex flex-col p-4 space-y-4">
        @if ($cartItems !== null)
            @forelse ($cartItems as $item)
                <div class="flex items-center justify-between pb-4 border-b">
                    <!-- Product Info -->
                    <div>
                        <h2 class="text-lg font-semibold">{{ $item['name'] }}</h2>
                        <p class="text-sm text-gray-600">Price: ${{ $item['price'] }}</p>
                        <p class="text-sm text-gray-600">Total: ${{ $item['total'] }}</p>
                    </div>

                    <!-- Quantity and Controls -->
                    <div class="flex items-center space-x-4">
                        <button wire:click="decrementQuantity({{ $item['id'] }})"
                            class="px-3 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">
                            -
                        </button>
                        <span class="font-semibold">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $item['id'] }})"
                            class="px-3 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">
                            +
                        </button>
                    </div>
                </div>
            @empty
                <!-- Empty Cart Message -->
                <div class="flex items-center justify-center h-full">
                    <h1 class="text-lg font-medium text-gray-500">No items in cart</h1>
                </div>
            @endforelse
        @endif
        <!-- Footer -->
        @if ($cartItems !== null)
            <div class="p-4 text-center border-t border-slate-400">
                <a class="w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-400"
                    href='{{ route('checkout') }}' wire:navigate>
                    Checkout
                </a>
            </div>
        @endif
    </div>
</div>
