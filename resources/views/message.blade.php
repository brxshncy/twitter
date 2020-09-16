@extends('layouts.main')
@section('title')
Message / Twitter
    @php
        $title = "message";
    @endphp
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('center')
    <div class="feed">
        <div class="feed-header">
            <h5>Messages</h5>
        </div>
        <div class="message" style="height:100%;">
            <div class="x">
                <h1>Under Development...</h1>
                <div class="home-x">
                    <a href="{{ route('home') }}">
                        <button>Go back to Homepage</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('other')
@include('layouts.others')
@endsection