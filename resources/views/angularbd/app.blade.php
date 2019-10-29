@extends('angularbd.layout')

@section('main-content')
    <div class="Meetup">

        @include('angularbd.partials.banner')

        @include('angularbd.partials.speakers')

        @include('angularbd.partials.sessions')

        @include('angularbd.partials.venue')

        @include('angularbd.partials.sponsors')

        @include('angularbd.partials.previous-meetup-gallery')

        @include('angularbd.partials.twitter')

        @include('angularbd.partials.footer')
    </div>
@endsection
