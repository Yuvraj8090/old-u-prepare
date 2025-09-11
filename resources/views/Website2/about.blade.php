@extends('layouts.website')

@section('content')

<style>
    p{
        text-align:justify;"
    }
</style>

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>About Us</h2>
          <ol>
            <li><a href="{{ asset('/') }}">Home</a></li>
            <li>About Us</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Story Intro Section ======= -->
    <section id="story-intro" class="story-intro">
      <div class="container">

        <div class="row">
            
          <div class="col-lg-12 pt-4 pt-lg-0 order-2 order-lg-1 content">
            <h3> U-PREPARE</h3><br>
            <p style="text-align:justify;" >The Uttarakhand Disaster Preparedness & Resilience Project (UPREPARE)
                        is a critical initiative aimed at bolstering disaster resilience and
                        preparedness in the state of Uttarakhand, typically supported by the
                        World Bank. The project focuses on assessing and mitigating the unique
                        risks posed by natural disasters, which are prevalent in the region,
                        including floods, landslides, earthquakes, and more. The project typically
                        involves a multi-faceted approach, including a thorough risk
                        assessment to identify vulnerabilities and hazards specific to the region.
                        One significant aspect is the development of resilient infrastructure,
                        capable of withstanding the forces of nature or minimizing their adverse
                        effects. Additionally, the implementation and improvement of early
                        warning systems are crucial components, aiding in timely alerts and
                        coordinated responses during emergencies. Capacity building and
                        policy advocacy form integral parts of the project, empowering local
                        authorities and communities to effectively manage disasters and
                        advocate for policies that prioritize disaster resilience and preparedness
                        at various levels. The project will support the recovery in terms of River
                        protection works, Road Protection works (Slopes), Reconstruction of
                        Bridges and strengthening the State Disaster Response Force.
            </p>
          </div>
          
           <div class="col-lg-12 pt-4 pt-lg-0 order-2 order-lg-1 content">
             <br><br>
            <h3> PROJECT FINANCE</h3>
            
            <br>
                 <div class="row">
                      <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content ">
                          <div style="border-radius:10px;" class="bg-success p-5 text-white">
                               <h4> PROPOSED PROJECT FINANCE</h4>
                                <br>
                                <h4> 168.75 MILLION </h4>
                          </div>
                         
                      </div>
                      <div class="col-lg-6  pt-lg-0 order-2 order-lg-1 content">
                          <div style="border-radius:10px;" class="text-center">
                                <img src="{{ asset('web/assets/img/about/about.jpeg') }}" width="100%"  height="200px;" />
                          </div>
                      </div>
                  </div>
          </div>
        </div>

      </div>
    </section><!-- End Story Intro Section -->

@stop

