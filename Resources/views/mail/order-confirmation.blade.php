@extends('laralite::mail.layout')

@section('content')
    <table bgcolor="#2a2a2a" border="0" cellpadding="20" cellspacing="0" width="600" style="width:600px!important;" id="emailContainer">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader" style="width:100%!important;background: #2A2A2A; color: white;">
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
                            <h1 style="margin-top: 0px; margin-bottom: 0px;">ORDER CONFIRMATION</h1>
                            <p>Thank you for your order.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
<!--                <h4 style="color: white; text-align: left !important;"><strong>Order Summary:</strong></h4>-->
                @foreach($form['order']->basket->products as $product)
                    <div style="color: white; text-align: center !important;">
                        <h2>Confirmation Code: {{ $form['order']->confirmation_code }}</h2>
                        <h2>Admit Quantity: {{ $product->quantity }}</h2>
                        <h2>Ticket Price: {{ $form['currency']['currency_symbol']  }} {{ $product->price }}</h2>
                        <h2>Tax & Fee: {{ $form['currency']['currency_symbol']  }} {{ number_format(($form['order']->basket->subtotals[0]->taxAmount + $form['order']->basket->subtotals[0]->serviceFee), 2) }}</h2>
                        <h2>Total Price: {{ $form['currency']['currency_symbol']  }} {{ number_format((($product->price + $form['order']->basket->subtotals[0]->taxAmount + $form['order']->basket->subtotals[0]->serviceFee) * $product->quantity), 2) }}</h2>
                        <h2>General Admission</h2>
                    </div>
                @endforeach
            </td>
        </tr>
    </table>
@endsection
