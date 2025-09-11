<!DOCTYPE html>
<html>
    <head>
        <title>Grievance Registration Confirmation</title>
    </head>

    <body>
        <div style="border: 1px solid #e3e5e8;border-radius: 10px;padding: 20px 40px;margin: 0 auto;max-width: 520px;background-color: white;">
            <img src="{{ asset('assets/img/logo.webp') }}">
            <h1 style="margin: 0;">@if($details->user_name){{ __('Hello, Sam Davis') }}@else{{ __('Hi there,') }}@endif</h1>

            <p>{{ $details->message }}</p>

            <h4>Team GRM {{ config('app.name') }}</h4>

            <hr>

            <div>
                <small>
                    You are receiving this email because you have registered a grievance at {{ url('/') }}. This is an
                    auto generated email please do not reply to this mailbox as it does not support incoming emails.
                </small>
            </div>
        </div>
    </body>
</html>
