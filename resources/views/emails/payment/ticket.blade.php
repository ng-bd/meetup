@extendS('emails.layout')

@section('content')
    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div style="mso-line-height-rule: exactly;mso-text-raise: 4px;">
            <h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #14161e;font-size: 22px;line-height: 31px;font-family: Bitter,Georgia,serif;">
                Hello, {{ $attendee->name }}</h1>
            <p style="Margin-top: 20px;Margin-bottom: 20px;"></p>
        </div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;Margin-bottom: 24px;text-align: center">
        Use this QR code to attend the meetup
        Scan QR code
    </div>
@endsection

@section('footer')
    Please PRINT and bring this ticket to the event entrance !!!
@stop
