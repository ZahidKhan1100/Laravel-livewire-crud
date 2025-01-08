<div id="cart-count">
    <x-cart></x-cart>
    @if ($cartCount > 0)
        <span class="absolute w-6 h-6 text-white bg-black rounded-full -top-4 -right-3">
            {{ $cartCount }}
        </span>
    @endif

</div>
