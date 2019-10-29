@extends('angularbd.sub-layout')
    @section('content')
        <main class="Meetup__payment">
            <h4 class="Meetup__sectionTitle">Ticket Price {{ env('EVENT_TICKET_PRICE') }}tk</h4>
            <p class="Meetup__sectionCopy">Our payment partner aamarPay is an Online Payment Gateway & Merchant Service Provider of Bangladesh. Aiming to provide best payment experience that an estore or customer can expect from a payment processor company.</p>
            <div class="Meetup__suspect">
                <div class="Meetup__suspectCopy">
                    <span>Name</span>
                    <span>{{ $attendee->name }}</span>
                </div>
                <div class="Meetup__suspectCopy">
                    <span>Email</span>
                    <span>{{ $attendee->email }}</span>
                </div>
                <div class="Meetup__suspectCopy">
                    <span>Phone</span>
                    <span>{{ $attendee->mobile }}</span>
                </div>
                <div class="Meetup__suspectCopy">
                    <span>Profession</span>
                    <span>{{ $attendee->profession }}</span>
                </div>
                <div class="Meetup__suspectCopy">
                    <span>Link</span>
                    <span>{{ $attendee->social_profile_url }}</span>
                </div>
                <div class="Meetup__suspectCopy">
                    <span>T-shirt</span>
                    <span>{{ data_get($attendee, 'tshirt') }}</span>
                </div>
                <div class="Meetup__suspectCopy">
                    <span>Note</span>
                    <span>{{ data_get($attendee, 'instruction') }}</span>
                </div>
                <button type="submit" class="Button Button--submit" onclick="location.href='https://thesoftking.com/ng-meetup/?attendee_id={{ $attendee->id }}&uuid={{ $attendee->uuid }}&name={{ $attendee->name }}&email={{ $attendee->email }}&mobile={{ $attendee->mobile }}'">{{ 'Pay '.env('EVENT_TICKET_PRICE').'tk' }}</button>
            </div>
        </main>
    @endsection
