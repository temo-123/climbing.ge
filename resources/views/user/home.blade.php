@extends('user.layouts.app')

@section('content')
    {{-- <li class="list-group-item text-muted top_menu_margin">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li> --}}
    <home-component />
@endsection
