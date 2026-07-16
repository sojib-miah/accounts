<h1>Cart Page</h1>

@php $total = 0; @endphp

@if (session('cart'))

    @foreach (session('cart') as $item)
        <p>

            {{ $item['name'] }}

            <br>

            {{ $item['variant_name'] ?? 'Default' }}
            :
            {{ $item['variant_value'] ?? '' }}

            <br>

            Qty :
            {{ $item['qty'] }}

            <br>

            Price :
            ৳ {{ $item['price'] }}

            <br>

            Total :
            ৳ {{ $item['price'] * $item['qty'] }}

        </p>
    @endforeach

    <h3>Total: {{ $total }}</h3>

    <a href="{{ route('checkout') }}">Checkout</a>
@else
    <h3>Cart is empty</h3>
@endif
