<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>


    <header class="relative flex items-center justify-between px-4 py-4 mb-3 bg-white shadow-xl">
        <h1 class="px-4 text-3xl">{{ config('app.name') }}</h1>
        <button id="cart-button" class="relative flex items-center justify-center">
            <livewire:cart.cart-count />
        </button>

        <div id="cart-items-container"
            class="absolute min-h-[60vh] w-[30vw] bg-slate-300 right-2 top-16 rounded-lg z-10 shadow-lg overflow-y-auto hidden">
            
                <livewire:cart.cart-items />
        </div>

    </header>
    <main>
        {{ $slot }}
    </main>
    @livewireScripts


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select the span and the cart items container
            const cartCountSpan = document.getElementById('cart-count');
            const cartItemsContainer = document.getElementById('cart-items-container');

            if (cartCountSpan && cartItemsContainer) {
                // Add click event listener to the span
                cartCountSpan.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent event bubbling to the button
                    cartItemsContainer.classList.toggle('hidden'); // Toggle the "hidden" class
                });

                // Optional: Hide the container if clicking outside
                document.addEventListener('click', function(event) {
                    if (!cartItemsContainer.contains(event.target) && !cartCountSpan.contains(event
                            .target)) {
                        cartItemsContainer.classList.add('hidden'); // Ensure it's hidden
                    }
                });
            } else {
                console.error('Cart count span or cart items container not found.');
            }
        });
    </script>

</body>

</html>
