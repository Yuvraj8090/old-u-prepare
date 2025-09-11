@extends('public.layout.base')

@section('page_title'){{ __('404 - Not Found') }}@endsection

@section('page_content')
    <div class="container">
        <div class="row">
            <div class="col-12 py-5 text-center">
                <h1>404 - Not Found</h1>
                <p class="m-0">The resource you are looking is not found on our server.</p>
            </div>
        </div>
    </div>
@endsection
