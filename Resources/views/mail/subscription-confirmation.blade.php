@php
/** @var \Modules\Laralite\Models\Subscription $subscriptionPlan */
/** @var \Modules\Laralite\Models\Customer\Subscription $subscription */
/** @var \Modules\Laralite\Models\Customer $customer */
/** @var \Modules\Laralite\Models\Discount|null $discount */
$discountAmount = $discount ? $discount->getDiscount($subscription->agreed_price) / 100 : 0;
$total = $subscription->agreed_price / 100;
@endphp

@extends('laralite::mail.layout')

@section('content')
    <table bgcolor="#2a2a2a" border="0" cellpadding="20" cellspacing="0" width="600" style="width:600px!important;" id="emailContainer">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader" style="width:100%!important;background: #2A2A2A; color: white;">
                    <tr>
                        <td align="center" valign="top">
                            <img class="site-logo" src="{{config('app.url')}}/images/trap-music-museum-logo.png" alt="" height="80">
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
                            <h1 style="margin-top: 0px; margin-bottom: 0px;">SUBSCRIPTION CONFIRMATION</h1>
                            <p>Thank you for subscribing to our {{$subscriptionPlan->name}} subscription plan.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="color: white; text-align: center !important;">
                    @if ($discount)
                        <h2>Discount Amount:  ${{ number_format($discountAmount, 2) }}</h2>
                        <h2>Initial Fee: ${{ number_format($total - $discountAmount) }}</h2>
                    @endif
                    <h2>Annual Fee: ${{ number_format($total, 2) }}</h2>
                    <h2>Expiry Date: {{ $subscription->expiry_date->format('dS \o\f F Y') }}</h2>
                </div>
            </td>
        </tr>
    </table>
@endsection
