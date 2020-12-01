<<<<<<< Updated upstream
@if($form['formType'] === 'general')
    <h1>General inquiry received @ Trap Music Museum</h1>

    <table class="table" width="100%">
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Name</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['name'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Email</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['email'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Type of inquiry</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['type'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Description</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['description'] }}</td>
        </tr>
    </table>

    <hr>
    <h2>END OF EMAIL</h2>
@endif

@if($form['formType'] === 'refunds')
    <h1>Refund inquiry received @ Trap Music Museum</h1>

    <table class="table" width="100%">
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Name</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['name'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Email</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['email'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Phone</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['phone'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Confirmation number</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['confirmationNumber'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Product purchased</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['productPurchased'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">What i'd like to do</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['whatToDo'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Description</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['description'] }}</td>
        </tr>
    </table>

    <hr>
    <h2>END OF EMAIL</h2>
@endif

@if($form['formType'] === 'waiver')
    <h1>Waiver Signed by {{ $form['name'] }} @ Trap Music Museum</h1>

    <table class="table" width="100%">
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Name</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['name'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Waiver signed</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['agreed'] == 1 ? 'YES' : 'NO' }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Minors</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['minors'] }}</td>
        </tr>
        <tr>
            <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Additional guest names</th>
            <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['additionalGuestNames'] }}</td>
        </tr>
    </table>

    <hr>
    <h2>END OF EMAIL</h2>
@endif
=======
@extends('laralite::mail.layout')

@section('content')

    @if($form['formType'] === 'general')
        <h1>General inquiry received @ Trap Music Museum</h1>

        <table class="table" width="100%">
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Name</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['name'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Email</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['email'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Type of inquiry</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['type'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Description</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['description'] }}</td>
            </tr>
        </table>

        <hr>
        <h2>END OF EMAIL</h2>
    @endif

    @if($form['formType'] === 'refunds')
        <h1>Refund inquiry received @ Trap Music Museum</h1>

        <table class="table" width="100%">
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Name</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['name'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Email</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['email'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Phone</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['phone'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Confirmation number</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['confirmationNumber'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Product purchased</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['productPurchased'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">What i'd like to do</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['whatToDo'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Description</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['description'] }}</td>
            </tr>
        </table>

        <hr>
        <h2>END OF EMAIL</h2>
    @endif

    @if($form['formType'] === 'waiver')
        <h1>Waiver Signed by {{ $form['name'] }} @ Trap Music Museum</h1>

        <table class="table" width="100%">
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Name</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['name'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Waiver signed</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['agreed'] == 1 ? 'YES' : 'NO' }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Minors</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['minors'] }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem; border-top: 1px solid #CCC" align="left">Additional guest names</th>
                <td style="padding: 1rem; border-top: 1px solid #CCC">{{ $form['additionalGuestNames'] }}</td>
            </tr>
        </table>

        <hr>
        <h2>END OF EMAIL</h2>
    @endif
@endsection
>>>>>>> Stashed changes
