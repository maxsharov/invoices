@component('mail::message')

Hello, {{ $name }}. Your invoice is ready! <br>
Amount: ${{ $amount }} <br>
To make the payment press this button:

@component('mail::button', ['url' => $url ])
Pay
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
