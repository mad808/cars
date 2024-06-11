@extends('layouts.app')
@section('title') @lang('app.cars') @endsection
@section('content')
    <div class="container-xxl py-3">
        <div class="d-block h3 text-dark fw-bold text-center border-bottom py-2 mb-3">
            <div class="h3 text-dark text-center">@lang('app.myPosts')</div>
        </div>
        <div class="row g-3">
            <div class="col-sm">
                @if($cars->count() > 0)
                    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                        @foreach($cars as $car)
                            <div class="col">@include('app.car')</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection