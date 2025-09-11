@extends('public.layout.base')

@section('page_title'){{ __('401 - Unauthorized') }}@endsection

@section('page_content')
    <div class="container">
        <div class="row">
            <div class="col-12 py-5 text-center">
                <h1>401 - Unauthorized</h1>
                <p class="m-0">You must authenticate yourself to access this section.</p>
            </div>
        </div>
    </div>
@endsection
