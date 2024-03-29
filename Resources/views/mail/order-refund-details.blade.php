@extends('laralite::mail.layout')

@section('content')
    <table bgcolor="#2a2a2a" border="0" cellpadding="20" cellspacing="0" width="600" style="width:600px!important;" id="emailContainer">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader" style="width:100%!important;background: #000; color: white;">
                    <tr>
                        <td align="center" valign="top">
                            <img class="site-logo" src="{{env('APP_URL')}}/images/trap-music-museum-logo.png" alt="" height="80">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" valign="top" style="color: #fff;">
                            <h1 style="margin-top: 0px; margin-bottom: 0px;">ORDER REFUND DETAILS</h1>
                            <p>Your order has been cancelled and amount is refunded to your account.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <p style="color: white; text-align: left !important;"><strong>Your Order Amount will be refunded in 3 to 7 bussiness days.</strong></p>
                <p style="color: white; text-align: left !important;"><strong>Your Order Id: {{ $form['order']->unique_id }}</strong></p>
                <p style="color: white; text-align: left !important;"><strong>Order Summary:</strong></p>
                <table border="1" cellpadding="20" cellspacing="0" width="100%" style="background-color: #FFFFFF; color: #333;border-collapse: collapse; border:solid 1px #333333;">
                    <tr bgcolor="#FFFFFF">
                        <td>SKU</td>
                        <td>Quantity</td>
                        <td>Price</td>
                        <td>Tax & Fee</td>
                    </tr>
                    @foreach($form['order']->basket->products as $product)
                        <tr>
                            <td align="left" width="75%">{{ $product->sku }}</td>
                            <td align="left" width="75%">&times;{{ $product->quantity }}</td>
                            <td align="left" width="75%" style="padding: 10px !important;">{{ $form['currency']['currency_symbol']  }} {{ $product->price }}</td>
                            <td align="left" width="75%" style="padding: 10px !important;">{{ $form['currency']['currency_symbol']  }} {{ number_format(($form['order']->basket->subtotals[0]->taxAmount + $form['order']->basket->subtotals[0]->serviceFee), 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" align="right">Subtotal: {{ $form['currency']['currency_symbol']  }} {{ number_format((($product->price + $form['order']->basket->subtotals[0]->taxAmount + $form['order']->basket->subtotals[0]->serviceFee) * $product->quantity), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
