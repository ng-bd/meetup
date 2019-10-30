@extends('angularbd.sub-layout')
    @section('content')
        <main class="Meetup__payment">
            <h4 class="Meetup__sectionTitle">
                @if($attendee->is_paid == '0')
                    Ticket Price {{ env('EVENT_TICKET_PRICE') }}tk
                @else
                    Congratulations! You have secured your seat by paying successfully.    
                @endif
            </h4>
            <p class="Meetup__sectionCopy"></p>
            <div class="Meetup__suspect">
                @if($attendee->is_paid == '1')
                <div class="Meetup__suspectCopy">
                    <span>id</span>
                    <span>{{ $attendee->uuid }}</span>
                </div>
                @endif
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
                @if($attendee->is_paid == '0')
                    <button type="submit" class="Button Button--submit" onclick="location.href='https://thesoftking.com/ng-meetup/?attendee_id={{ $attendee->id }}&uuid={{ $attendee->uuid }}&name={{ $attendee->name }}&email={{ $attendee->email }}&mobile={{ $attendee->mobile }}'">{{ 'Pay '.env('EVENT_TICKET_PRICE').'tk' }}</button>
                @endif
            </div>
        </main>
    @endsection
