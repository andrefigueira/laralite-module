<h1>Order Confirmation</h1>

<p>Thank you for your order.</p>

<h3>Order Summary</h3>

<table width="100%" style="border: 1px solid #FFF;">
    <tr>
        <td>SKU</td>
        <td>Quantity</td>
        <td>Price</td>
    </tr>
    @foreach($form['order']->basket->products as $product)
        <tr>
            <td>{{ $product->sku }}</td>
            <td>&times;{{ $product->quantity }}</td>
            <td>${{ $product->price }}</td>
        </tr>
    @endforeach
</table>