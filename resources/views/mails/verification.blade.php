
<x-mail::message>
    <h1>Welcome, {{ $username }}!</h1>
    <p>Please verify your email using the code: <strong>{{ $otp }}</strong></p>
    <p>This code is valid for <strong>10 minutes</strong>.</p>
    <p>Thank you for registering!</p>
    <footer>
        {{ config('app.name') }} &copy; {{ date('Y') }}
    </footer>
</x-mail::message>
