@extends('layouts.website')

@section('content')


    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>About / Project Structure </h2>
          <ol>
            <li><a href="{{ asset('/') }}">Home</a></li>
            <li>Project Structure</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Story Intro Section ======= -->
    <section id="story-intro" class="story-intro">
      <div class="container">

        <div class="row">
          <div class="col-lg-12 pt-4 pt-lg-0 order-2 order-lg-1 content">
                <img src="{{ asset('web/assets/img/project-structure.jpeg')  }}" width="100%"  />
           </div>
        </div>

      </div>
    </section><!-- End Story Intro Section -->

 
@stop