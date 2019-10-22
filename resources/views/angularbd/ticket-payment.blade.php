@extends('angularbd.sub-layout')

@section('content')
    <div class="loveMeLikeYouDo">
        Name: {{ $attendee->name }}<br>
        Email: {{ $attendee->email }}<br>
        Phone: {{ $attendee->mobile }}<br>
        T-Shirt: {{ data_get($attendee, 'misc.tshirt') }}<br>
        Ticket Price : {{ env('EVENT_TICKET_PRICE') }}<br>
    </div>
{!!
    aamarpay_post_button([
        'cus_name'  => $attendee->name,
        'cus_email' => $attendee->email,
        'cus_phone' => $attendee->mobile,
        'opt_a' => $attendee->id,
    ], env('EVENT_TICKET_PRICE'), 'Pay Now', 'Button Button--submit')
!!}
@endsection
