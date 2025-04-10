<x-mail::message>
    <h1>Hello, {{ $firstname }} {{ $lastname }}!</h1>
    <p> Please reset your password using the below code</p>
    <p>Your OTP code is: <strong>{{ $token }}</strong></p>
    <p>This code is valid for <strong>10 minutes</strong>.</p>
    <p>Thanks</p>
    <footer>
        {{ config('app.name') }} &copy; {{ date('Y') }}
    </footer>
</x-mail::message>
