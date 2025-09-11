@extends('layouts.app')

@section('content')
<div class="container-fluid">
     <div class="row">
         <div class="col-md-12">
            <div id="demo" class="carousel slide" data-bs-ride="carousel">

  <!-- Indicators/dots -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
     <button type="button" data-bs-target="#demo" data-bs-slide-to="3"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="4"></button>
  </div>
  
  <!-- The slideshow/carousel -->
  <div class="carousel-inner">
   
                <div class="carousel-item active">
                      <img src="{{ asset('asset/web/IMG20240426151005.jpg') }}" loading="lazy" class="d-block w-100" height="600px" >
                </div>
                <div class="carousel-item">
                      <img src="{{ asset('asset/web/IMG20240426151005.jpg') }}" loading="lazy" class="d-block w-100" height="600px"  >
                </div>
                <div class="carousel-item">
                      <img src="{{ asset('asset/web/IMG20240426150955.jpg') }}" loading="lazy" class="d-block w-100" height="600px" >
                </div>
                
                <div class="carousel-item">
                      <img src="{{ asset('asset/web/one.jpeg') }}" class="d-block w-100" loading="lazy" height="600px" >
                </div>
                <div class="carousel-item">
                      <img src="{{ asset('asset/web/two.jpeg') }}" class="d-block w-100" loading="lazy" height="600px" >
                </div>

  <!-- Left and right controls/icons -->
  <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


         </div>
         </div>
            <div class="col-md-8 col-xs-12">
                  <p class='p'>
                   The U-PREPARE project aims to enhance the climate and disaster resilience of critical public infrastructure and strengthen disaster risk management capacity in Uttarakhand. Its Project Development Objective (PDO) encompasses improving infrastructure access, early warning systems, and fire stations to benefit communities. The project's components include infrastructure resilience enhancement, emergency preparedness and response improvement, forest and general fire prevention and management, project management, and a contingent emergency response component. With an expected reach of approximately 10 million people, including a focus on women, the project targets various beneficiaries, such as those reliant on resilient infrastructure and enhanced emergency services. The World Bank's involvement reflects its expertise in disaster risk management (DRM) and climate adaptation, aiming to build upon past successes while emphasizing capacity building and community engagement. Key lessons incorporated into the project design include the importance of complementing physical investments with capacity building, engaging communities for effective DRM, and leveraging private capital for sustainability.
                  </p>
            </div>
            <div  class="col-md-4 col-xs-12">
                <div style="background-color:rgb(209 200 193);margin:10px;padding:5px 10px;">
                    
                    <h5 style="padding-top:30px;">NOTICE BOARD</h5>
                    <br>
                    <marquee style="height:200px;" direction="up" behavior="scroll" scrollamount="2">
                        <p>This is a paragraph that scrolls from top to bottom. You can adjust the speed and direction of the scrolling text using the attributes of the marquee tag.</p>
                    </marquee>
                </div>
            </div>
    </div>
</div>
@stop