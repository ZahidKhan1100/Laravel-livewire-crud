<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>


    <header class="flex items-center justify-between px-4 py-4 mb-3 bg-white shadow-xl">
        <h1 class="px-4 text-3xl">{{ config('app.name') }}</h1>
        <button class="relative flex items-center justify-center">
            <x-cart></x-cart>
            <span id="cart-count"
                class="absolute w-6 h-6 text-white bg-black rounded-full -top-4 -right-3">{{ session('cart') ? count(session('cart')) : 0 }}</span></button>
    </header>
    <main>
        {{ $slot }}
    </main>
    @livewireScripts

    <script></script>
    <script>
        // Listen for the 'cart-updated' event and update the cart count
        Livewire.on('cart-updated', (event) => {
            console.log(event[0].cartItems);

            const cartCountElement = document.getElementById('cart-count');
            cartCountElement.textContent = event[0].count; // Update cart count display
        });
    </script>
</body>

</html>
