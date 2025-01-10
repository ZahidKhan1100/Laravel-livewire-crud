<div class="container px-4 py-6 mx-auto">
    <h1 class="mb-4 text-2xl font-bold">Orders</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-2 text-sm font-semibold text-left text-gray-600">Order Number</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left text-gray-600">Customer</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left text-gray-600">Total</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left text-gray-600">Status</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left text-gray-600">Notes</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $order->order_number }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            {{ $order->customer_name }}<br>
                            <span class="text-xs text-gray-500">{{ $order->customer_email }}</span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">${{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" 
                                    class="block w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                @foreach (['pending', 'processing', 'completed', 'declined', 'canceled'] as $status)
                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            <textarea wire:blur="updateNotes({{ $order->id }}, $event.target.value)" 
                                      class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                      rows="2">{{ $order->notes }}</textarea>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            <button wire:click="deleteOrder({{ $order->id }})" 
                                    class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-sm text-center text-gray-700">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
