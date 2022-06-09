@extends('laralite::mail.layout')

@section('content')
    <table bgcolor="#2a2a2a" border="0" cellpadding="20" cellspacing="0" width="600" style="width:600px!important;" id="emailContainer">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader" style="width:100%!important; color: white;">
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
                            <h1 style="margin-top: 0px; margin-bottom: 0px;">Reset Password</h1>
                            <p>You are receiving this email because we received a password reset request for your account.</p>
                            <a href="{{ $url }}" class="reset-btn-password">Reset Password</a>
                            <p>This password reset link will expire in 60 minutes.</p>
                            <p>If you did not request a password reset, no further action is required.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection

<style>
    .reset-btn-password {
        background-color: white;
        padding: 12px 15px;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        position: relative;
        -webkit-text-size-adjust: none;
        border-radius: 4px;
        color: #000;
        display: inline-block;
        overflow: hidden;
        text-decoration: none;
    }
</style>
