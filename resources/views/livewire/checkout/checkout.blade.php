<div class="w-[100vw] px-4 mx-auto mt-8 grid grid-cols-12 gap-6 justify-center items-center min-h[80vh]">
    <!-- Cart Items (8 Columns on large screens) -->
    <div class="col-span-12 lg:col-span-8">
        <table class="min-w-full bg-white border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Total Price</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($items !== null)
                    @forelse ($items as $item)
                        <tr class="">
                            <td class="px-4 py-2">{{ $item['name'] }}</td>
                            <td class="px-4 py-2">{{ $item['price'] }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center">
                                    <button wire:click="decrementQuantity({{ $item['id'] }})"
                                        class="px-2 py-1 text-white bg-red-500 rounded">-</button>
                                    <span class="px-4">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity({{ $item['id'] }})"
                                        class="px-2 py-1 text-white bg-green-500 rounded">+</button>
                                </div>
                            </td>
                            <td class="px-4 py-2">{{ $item['price'] * $item['quantity'] }}</td>
                            <td class="px-4 py-2">
                                <button wire:click="removeItem({{ $item['id'] }})"
                                    class="px-4 py-2 text-white bg-red-500 rounded">Remove</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">No items in the cart</td>
                        </tr>
                    @endforelse
                    @elseif ($items === null)
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">No items in the cart</td>
                        </tr>
                @endif
            </tbody>
        </table>

    </div>

    <!-- Order Submission Form (4 Columns on large screens) -->
    <div class="col-span-12 lg:col-span-4">
        <div class="flex items-center justify-between p-4 mt-6 bg-gray-100 rounded">
            <span class="text-xl font-semibold">Total Amount:</span>
            <span class="text-xl font-bold text-green-600">
                ${{ collect($items)->sum(fn($item) => $item['price'] * $item['quantity']) }}
            </span>
        </div>
        <form wire:submit.prevent="submitOrder" class="p-6 bg-white rounded">
            <h2 class="mb-4 text-lg font-semibold">Submit Your Order</h2>
            <div class="mb-4">
                <label for="customer_name" class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" id="customer_name" wire:model="customer_name"
                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-green-200"
                    placeholder="Enter your name">
            </div>
            <div class="mb-4">
                <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="customer_email" wire:model="customer_email"
                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-green-200"
                    placeholder="Enter your email">
            </div>
            <div class="mb-4">
                <label for="customer_address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea id="customer_address" wire:model="customer_address"
                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-green-200" placeholder="Enter your address"></textarea>
            </div>
            <button type="submit" class="w-full py-2 text-white bg-green-500 rounded hover:bg-green-600" wire:click="submitOrder">
                Submit Order
            </button>
        </form>
    </div>
    <livewire:toaster.toaster />

</div>
