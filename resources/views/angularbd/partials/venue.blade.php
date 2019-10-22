<div class="Meetup__venue" id="venue">
    <h4 class="Meetup__venueTitle">Venue</h4>
    <p class="Meetup__venueCopy">{{ env('EVENT_VENUE') }} <br>{{ env('EVENT_ADDRESS') }}
        <br><a
            href="{{ env('EVENT_GOOGLE_MAP') }}"
            target="_blank">[Google Map]</a></p>
    <h4 class="Meetup__venueTitle">Time</h4>
    <p class="Meetup__venueCopy">{{ \Illuminate\Support\Carbon::parse(env('EVENT_DATE'))->format('d F Y') }} <br>3PM -7PM</p>
</div>
