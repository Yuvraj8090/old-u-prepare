@extends('layouts.app')

@section('content')
<style>
    .col-md-3,.col-md-9{
        padding:20px;
        text-align:justify;
    }
</style>
<div class='container-fluid'>
    <div class="row">
            <div class="col-md-6 col-xs-12">
                <img src="{{ asset('asset/web/home_left.jpeg') }}" class="img-fluid rightImage"  loading="lazy" alt="Right Image">
              </div>
            <div class="col-md-6 col-xs-12">
                  <br>
                <h3>ABOUT U-PREPARE</h3>
                <p class="about-text">
                    The Uttarakhand Disaster Preparedness & Resilience Project (UPREPARE)
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
            
            <!--<div class="row">-->
            <!--    <h3 style="background-color:rgb(209 200 193);text-align:center;" >PROJECT FINANCE</h3>-->
            
            <!--    <div class="col-md-6 col-xs-12">-->
            <!--        sadsadasd-->
            <!--    </div>-->
                
            <!--    <div class="col-md-6 col-xs-12">-->
            <!--        dswdsadd-->
            <!--    </div>-->
            <!--</div>-->
            
            <div class="row">
                <h3 style="background-color:rgb(209 200 193);text-align:center;padding:12px;" >PROJECT COMPONENTS</h3>
                
                <div class="col-md-3">
                   
                </div>
                
                <div class="col-md-9">
                    <h4>Enhancing Infrastructure Resilience </h4>
                    <p>Aims to fortify critical infrastructure against climate and disaster risks by enhancing road infrastructure resilience, improving health service facilities' readiness, and establishing disaster shelters in vulnerable areas. Measures include reinforcing bridges, implementing bioengineering solutions to mitigate landslides, retrofitting health centers for earthquake and fire safety, and constructing energy-efficient disaster shelters along major routes. These efforts align with the project's objective of integrating resilience into infrastructure planning to better withstand environmental hazards in Uttarakhand.</p>
                </div>

                <div class="col-md-9">
                    <h4>Improving Emergency Preparedness and Response </h4>
                    <p>
                        Involves establishing a State Emergency Operations Center (SEOC) with a Centralized Incident Command System to streamline coordination, reviewing and strengthening multi-agency institutional frameworks, and conducting community awareness campaigns for disaster risk management (DRM). Additionally, efforts are directed towards improving hydromet and early warning systems by enhancing forecast accuracy, establishing a comprehensive multi-hazard EWS for timely alerts, developing tailored hydromet tools for key stakeholders, and conducting training sessions for DRM officials and communities. Strengthening the State Disaster Response Force (SDRF) includes constructing outdoor training facilities, providing essential search and rescue equipment, and offering comprehensive training on equipment handling and maintenance.
                    </p>
                </div>
                
                <div class="col-md-3">
                 
                </div>
                
                 <div class="col-md-3">
                   
                </div>
                
                 <div class="col-md-9">
                    <h4>Preventing and Managing Forest and General Fires </h4>
                    <p>
                        Focuses on enhancing capacities for preventing and managing forest and general fires. Strategies involve conducting fire risk assessments, developing state-level fire management plans, upgrading firefighting capabilities with advanced technologies, and implementing community-based fire prevention initiatives. Additionally, efforts target forest fire prevention and management, emphasizing early detection, policy assessments, equipment procurement, and the establishment of incentive-based programs for sustainable forest management. These measures aim to mitigate fire risks, promote biodiversity, and ensure long-term sustainability.
                    </p>
                </div>

                <div class="col-md-9">
                    <h4>Project Management  </h4>
                    <p>
                        Facilitate effective project management and fostering knowledge sharing. It ensures day-to-day coordination by supporting various aspects like financial management, procurement, environmental and social management, communication, monitoring and evaluation, and stakeholder engagement. Additionally, it aims to establish a Lighthouse Uttarakhand platform to disseminate lessons learned from the project, enhance capacity building, and promote the exchange of knowledge and experiences with other states in India through institutional partnerships.
                    </p>
                </div>
                
                  <div class="col-md-3">
                    
                </div>
                
                <div class="col-md-3">
                    
                </div>
                
                <div class="col-md-9">
                    <h4>Contingent Emergency Response Component </h4>
                    <p>
                        Serves as a crucial mechanism designed to swiftly reallocate resources in response to eligible crises or emergencies. Its primary objective is to provide immediate support by redirecting credit proceeds from other components as needed, ensuring a rapid and effective response to unforeseen events or disasters.
                    </p>
                </div>
                
            </div>
    </div>
</div>
@stop