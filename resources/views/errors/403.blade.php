@extends('public.layout.base')

@section('page_title'){{ __('403 - Access Denied') }}@endsection

@section('page_content')
    <div class="container">
        <div class="row">
            <div class="col-12 py-5 text-center">
                <h1>403 - Access Denied</h1>
                <p class="m-0">You don't have enough permissions to access this section.</p>
            </div>
        </div>
    </div>
@endsection
