@extends('public.layout.base')

@section('page_title'){{ __('419 - Session Expired') }}@endsection

@section('page_content')
    <div class="container d-flex">
        <div class="row w-100 align-items-center justify-content-center">
            <div class="col-12 py-5 text-center">
                <h1>419 - Session Expired</h1>
                <p>Your logged in session has expired. Kindly login again to continue.</p>
                <p class="m-0">
                    <a href="{{ route('mis.login') }}" class="btn btn-primary">Login to continue!</a>
                </p>
            </div>
        </div>
    </div>
@endsection
