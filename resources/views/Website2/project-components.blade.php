@extends('layouts.website')

@section('content')


 <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>About / Project Components</h2>
          <ol>
            <li><a href="{{ asset('/') }}">Home</a></li>
            <li>Project Components</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->
    
    
     <!-- ======= Featured Members Section ======= -->
    <section id="featured-members" class="featured-members">
      <div class="container">
            
        <h2 class="text-center" style="font-weight:700;" > PROJECT COMPONENTS</h2><br><br>
        <div class="row content">
              
          <div class="col-lg-6">
            <img src="{{ asset('web/assets/img/about/Enhancing.jpeg') }}" style="height:260px;width:100%;border-radius:10px;" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-3 pt-lg-0">
            <h3>Enhancing Infrastructure Resilience </h3>
            <p class="fst-italic">
              Aims to fortify critical infrastructure against climate and disaster risks by enhancing road infrastructure resilience, improving health service facilities' readiness, and establishing disaster shelters in vulnerable areas. Measures include reinforcing bridges, implementing bioengineering solutions to mitigate landslides, retrofitting health centers for earthquake and fire safety, and constructing energy-efficient disaster shelters along major routes. These efforts align with the project's objective of integrating resilience into infrastructure planning to better withstand environmental hazards in Uttarakhand.
            </p>
          </div>
        </div>

        <div class="row content">
          <div class="col-lg-6 order-1 order-lg-2">
            <img src="{{ asset('web/assets/img/about/Improving.jpeg') }}" style="height:350px;width:100%;border-radius:10px;" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 order-2 order-lg-1 pt-3 pt-lg-0">
            <h3>Improving Emergency Preparedness and Response</h3>
            <p class="fst-italic">
                                     Involves establishing a State Emergency Operations Center (SEOC) with a Centralized Incident Command System to streamline coordination, reviewing and strengthening multi-agency institutional frameworks, and conducting community awareness campaigns for disaster risk management (DRM). Additionally, efforts are directed towards improving hydromet and early warning systems by enhancing forecast accuracy, establishing a comprehensive multi-hazard EWS for timely alerts, developing tailored hydromet tools for key stakeholders, and conducting training sessions for DRM officials and communities. Strengthening the State Disaster Response Force (SDRF) includes constructing outdoor training facilities, providing essential search and rescue equipment, and offering comprehensive training on equipment handling and maintenance.

            </p>
            <!--<p>-->
            <!--  Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate-->
            <!--  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in-->
            <!--  culpa qui officia deserunt mollit anim id est laborum-->
            <!--</p>-->
          </div>
        </div>

        <div class="row content">
          <div class="col-lg-6">
             <img src="{{ asset('web/assets/img/about/Preventing.jpeg') }}" style="height:320px;width:100%;border-radius:10px;" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-3 pt-lg-0">
            <h3>Preventing and Managing Forest and General Fire</h3>
    
            <p>Focuses on enhancing capacities for preventing and managing forest and general fires. Strategies involve conducting fire risk assessments, developing state-level fire management plans, upgrading firefighting capabilities with advanced technologies, and implementing community-based fire prevention initiatives. Additionally, efforts target forest fire prevention and management, emphasizing early detection, policy assessments, equipment procurement, and the establishment of incentive-based programs for sustainable forest management. These measures aim to mitigate fire risks, promote biodiversity, and ensure long-term sustainability.
            </p>
         
          </div>
        </div>

        <div class="row content">
          <div class="col-lg-6 order-1 order-lg-2">
              <img src="{{ asset('web/assets/img/about/management.jpeg') }}" style="height:320px;width:100%;border-radius:10px;" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 order-2 order-lg-1 pt-3 pt-lg-0">
            <h3>Project Management</h3>
            <p class="fst-italic">
                                     Facilitate effective project management and fostering knowledge sharing. It ensures day-to-day coordination by supporting various aspects like financial management, procurement, environmental and social management, communication, monitoring and evaluation, and stakeholder engagement. Additionally, it aims to establish a Lighthouse Uttarakhand platform to disseminate lessons learned from the project, enhance capacity building, and promote the exchange of knowledge and experiences with other states in India through institutional partnerships.

            </p>
            <!--<p>-->
            <!--  Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate-->
            <!--  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in-->
            <!--  culpa qui officia deserunt mollit anim id est laborum-->
            <!--</p>-->
          </div>
        </div>

      </div>
    </section><!-- End Featured Members Section -->



@stop