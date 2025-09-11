@extends('layouts.admin')

@section('content')

<div>
    <div style="width:100%;" class="page-title">
        <div style="width:100%;" class="title_left">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            <h4>Project Name : {{ $data->name }} | Project Id- {{ $data->number }} </h4>
            
        </div>
    </div>


    <div class="clearfix"></div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
            <li class="breadcrumb-item active"><a href="#">Projects Details</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">

            <!-- code 9 feb change for design change -->
            <div class="row">
                <div class="col-md-6">
                    @include('admin.project.components.project-details')
                </div>
                <div class="col-md-6">
                    @include('admin.project.components.approve-details')
                </div>
                <div class="col-md-12">
                    @include('admin.project.components.project-location')
                </div>
              
                @if(!empty($contracts))
                <div class="col-md-12">
                    @include('admin.project.components.contract')
                </div>
                <div class="col-md-12">
                    @include('admin.project.components.contractor')
                </div>
                @else
                <div class="col-md-12">
                    @include('admin.project.components.empty',['headline' => 'PROJECT CONTRACT & CONTRACTOR DETAILS' , 'content' => 'Project Contract & Contractor step in process...'])
                </div>
                @endif
                
                
                <div class="col-md-12">
                    @if(!empty($defineProject))
                        @include('admin.environment-social.component.defineProject')
                    @else
                        @include('admin.project.components.empty',['headline' => 'DEFINE PROJECT' , 'content' => ' Define Project step in process...'])
                    @endif
                </div>
                
        @if(isset($environmentCalculation)) 
            @include('admin.project.components.enviornmentSocialTestReport',['data' => $environmentCalculation,'headline' => 'ENVIRONMENT SAFEGUARD ACTIVITIES','module' => '1']);
        @elseif(isset($socialCalculation))
            @include('admin.project.components.enviornmentSocialTestReport',['data' => $socialCalculation,'headline' => 'SOCIAL SAFEGUARD ACTIVITIES','module' => '2']);
        @endif
                
            @if(false)

                <div class="col-md-12">
                    @if(!empty($milestones))
                        @if(count($milestones) > 0)
                            @include('admin.environment-social.component.milestones')
                        @endif
                    @else
                        @include('admin.project.components.empty',['headline' => 'PROJECT MILESTONES' , 'content' => 'Project Milestones step in process...'])
                    @endif
                </div>
                
            @endif
                

        </div>
    </div>

</div>
</div>

@stop

