@extends('angularbd.layout')

@section('main-content')
    <div class="Meetup">
        <header class="Meetup__header Meetup__header--ticket">
            <a class="Meetup__back" href="{{ route('angularbd.index') }}" ><img src="{{ asset('angularbd/icons/back.svg') }}" alt=""></a>
            @include('angularbd.partials.logo')
        </header>

        @yield('content')

        <div class="Credit">Crafted by <a href="https://www.facebook.com/zafree" target="_blank">Zafree</a><br>Backend Support by <a href="https://www.facebook.com/to.shipu" target="_blank">Shipu</a></div>
    </div>
@endsection
