<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div style="text-align: center">
    <a style="text-decoration: none;transition: opacity 0.1s ease-in;color: #c3ced9;"
       href="{{ env('EVENT_FACEBOOK_LINK') }}">
        <img style="display: block;height: auto;width: 200px;border: 0;"
             src="{{ public_path('angularbd/images/logo.png') }}" alt="{{ env('EVENT_TITLE') }}"
             width="65">
    </a>
    <br>
    <br>
    <i>Hello, i am</i>
    <h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #14161e;font-size: 22px;line-height: 31px;font-family: Bitter,Georgia,serif;">
        {{ $attendee->name }}
    </h1>
    @include('emails.payment.qr_code')
</div>
<br>
<br>
<br>
<br>

@php $start = 1; @endphp
@foreach(range(1, 3) as $row)
    <div style="text-align: center">
        @foreach(range($start, ($row * 4)) as $number)
            @if($number >= 9) @break @endif

            <a style="text-decoration: underline;transition: opacity 0.1s ease-in;color: #f4645f;"
               href="#">
                <img
                    style="border: 0;display: block;height: auto;width: 120px;max-width: 400px;"
                    alt="" width="260"
                    src="{{ public_path('angularbd/images/sponsor-'.$number.'.png') }}">
            </a>
        @endforeach
    </div>
    @php $start += 4; @endphp
@endforeach
<br>
<br>
<br>
<br>
<div style="font-size: 15px;line-height: 19px;margin-top: 20px;text-align: center">
    Event Date: <b>{{ \Carbon\Carbon::parse(env('EVENT_DATE'))->format('d M Y h:i a') }}</b><br>
    Venue: <b>{{ env('EVENT_VENUE') }}</b><br>
    Address: <b>{{ env('EVENT_ADDRESS') }}</b><br>
    Google Map: <a target="_blank"
                   href="{{ env('EVENT_GOOGLE_MAP') }}">{{ env('EVENT_GOOGLE_MAP') }}</a>

    <br>
    <br>
    <br>
    <br>
    <br>
    Please PRINT and bring this ticket to the event entrance!!!
</div>
</body>
</html>
