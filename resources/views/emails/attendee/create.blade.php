@extendS('emails.layout')

@section('content')
    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div style="mso-line-height-rule: exactly;mso-text-raise: 4px;">
            <h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #14161e;font-size: 22px;line-height: 31px;font-family: Bitter,Georgia,serif;">
                Hello, {{ $attendee->name }}</h1>
            <p style="Margin-top: 20px;Margin-bottom: 20px;">Congratulations! You've
                successfully completed the registration for {{ env('EVENT_TITLE') }}. Thanks for
                being with us and we'll share what's happening around.</p>
        </div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;Margin-bottom: 24px;">
        @if($attendee->type == \App\Enums\AttendeeType::ATTENDEE)
        <div class="btn btn--flat btn--large" style="text-align:center;">
            <!--[if !mso]--><a
                style="border-radius: 0;display: inline-block;font-size: 14px;font-weight: bold;line-height: 24px;padding: 12px 24px;text-align: center;text-decoration: none !important;transition: opacity 0.1s ease-in;color: #ffffff !important;background-color: #1b141e;font-family: Open Sans, sans-serif;"
                href="{{ route('ticket.payment', $attendee->id) }}">Pay Now</a><!--[endif]-->
        </div>
        @else
        <p style="Margin-top: 20px;Margin-bottom: 20px;">
            {{ env('EVENT_TITLE') }}<br>
            Date: {{ \Carbon\Carbon::parse(env('EVENT_DATE'))->format('d M Y h:i a') }}<br>
            Venue: {{ env('EVENT_VENUE') }}<br>
            Address: {{ env('EVENT_ADDRESS') }}<br>
            Google Map: <a target="_blank" href="{{ env('EVENT_GOOGLE_MAP') }}">{{ env('EVENT_GOOGLE_MAP') }}</a>
        </p>
        @endif
    </div>
@stop

@section('footer')
    You are receiving this because you joined the {{ env('EVENT_TITLE') }}.
    If you know someone who would enjoy this event please consider sharing
    it.
@stop
