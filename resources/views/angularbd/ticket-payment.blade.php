@extends('angularbd.sub-layout')

@section('content')
Name: {{ $attendee->name }}<br>
Email: {{ $attendee->email }}<br>
Phone: {{ $attendee->mobile }}<br>
T-Shirt: {{ data_get($attendee, 'misc.tshirt') }}<br>
Ticket Price : {{ env('EVENT_TICKET_PRICE') }}<br>
{!!
    aamarpay_post_button([
        'cus_name'  => $attendee->name,
        'cus_email' => $attendee->email,
        'cus_phone' => $attendee->mobile,
        'opt_a' => $attendee->id,
    ], env('EVENT_TICKET_PRICE'), '<i class="fa fa-money">Pay Now</i>', 'btn btn-sm btn-success')
!!}
@endsection
