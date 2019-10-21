@extendS('emails.layout')

@section('content')
    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div style="mso-line-height-rule: exactly;mso-text-raise: 4px;">
            <h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #14161e;font-size: 22px;line-height: 31px;font-family: Bitter,Georgia,serif;">
                Hello, {{ $attendee->name }}</h1>
            <p style="Margin-top: 20px;Margin-bottom: 20px;">Congratulations, your payment has been successfully completed for {{ env('EVENT_TITLE') }}. Thank you for joining in this event. Hopefully we'll pass a good time.</p>
        </div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;Margin-bottom: 24px;">
        Please print the attached ticket. This will show when you participate in the event or you can show below QR code.
        @include('emails.payment.qr_code')
    </div>
@endsection

@section('footer')
    You are receiving this because your payment has been successfully completed for {{ env('EVENT_TITLE') }}.
@stop
