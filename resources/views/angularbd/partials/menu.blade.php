<header class="Meetup__header">
    @include('angularbd.partials.logo')
    <div class="Meetup__navigation">
        <a class="Meetup__navigationLink Meetup__navigationLink--ticket" href="{{ route('buy.ticket') }}">Buy Tickets</a>
        <a class="Meetup__navigationLink" href="#speakers">Speakers</a>
        <a class="Meetup__navigationLink" href="#sessions">Sessions</a>
        <a class="Meetup__navigationLink" href="#venue">Venue</a>
        <a class="Meetup__navigationLink" href="#sponsors">Sponsors</a>
        <a class="Meetup__navigationLink" href="#gallery">Gallery</a>
    </div>
</header>
