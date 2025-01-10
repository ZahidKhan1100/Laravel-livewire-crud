<div x-data="{ show: @entangle('show') }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000);
$wire.on('hide-toaster', () => { show = false });"
    class="fixed px-4 py-2 rounded shadow-lg bottom-4 right-4 {{ $type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}"
    style="display: none;">
    {{ $message }}
</div>
