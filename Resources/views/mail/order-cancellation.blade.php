@extends('laralite::mail.layout')

@section('content')
    <table bgcolor="#2a2a2a" border="0" cellpadding="20" cellspacing="0" width="600" style="width:600px!important;" id="emailContainer">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader" style="width:100%!important;background: #000; color: white;">
                    <tr>
                        <td align="center" valign="top">
                            <img class="site-logo" src="https://trapmusicmuseum.us/images/trap-music-museum-logo.png" alt="" height="80">
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
                            <h1 style="margin-top: 0px; margin-bottom: 0px;">ORDER CANCELATION</h1>
                            <p>Your order has been canceled and amount is refunded to your account.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <p style="color: white; text-align: left !important;"><strong>Order Summary:</strong></p>
                <table border="1" cellpadding="20" cellspacing="0" width="100%" style="color: #fff;border-collapse: collapse; border:solid 1px #333333;">
                    <tr bgcolor="#333333">
                        <td>Order Id</td>
                        <!--                        <td>Refund Id</td>-->
                        <td>Amount</td>
                    </tr>
                    <tr>
                        <td align="left" width="75%">{{ $form['order']->unique_id }}</td>
                    <!--                            <td align="left" width="75%">&times;{{ $form['order']->payment_processor_result->id }}</td>-->
                        <td align="left" width="75%">${{ $form['order']->amount }}</td>
                    </tr>
                    <!--                    <tr>
                                            <td colspan="5" align="right">Subtotal</td>
                                        </tr>-->
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailFooter">
                    <tr>
                        <td align="center" valign="top" style="color: #fff;">
                            This is where my footer content goes.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
