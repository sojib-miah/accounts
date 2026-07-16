<h1>Checkout</h1>

<form action="{{ route('order.store') }}" method="POST">
    @csrf

    <input type="text" name="customer_name" placeholder="Name" required><br>

    <input type="text" name="customer_phone" placeholder="Phone" required><br>

    <input type="email" name="customer_email" placeholder="Email"><br>

    <input type="text" name="city" placeholder="City"><br>

    <textarea name="address" placeholder="Address" required></textarea><br>

    <select name="payment_method">
        <option value="cash">Cash On Delivery</option>
    </select>

    <button type="submit">Place Order</button>
</form>
