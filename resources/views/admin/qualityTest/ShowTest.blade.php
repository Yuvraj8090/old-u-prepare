@extends('layouts.admin')

@section('content')

<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
   @include('admin.include.backButton')

    <h4>
          @if($moduleType == 1)
        ENVIRONMENT SAFEGUARD 
    @elseif($moduleType == 2)
        SOCIAL SAFEGUARD 
    @else
        TEST DETAILS
    @endif
        |  
    @if($type  == 1 )
        Pre-Construction
    @elseif($type  == 2)
        Construction
    @elseif($type  == 3)
        Post-Construction
    @else
        N/A
    @endif 
         Activities 
        @if(isset($parent->name))
           | {{$parent->name}}
        @endif 
        </h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">@if($type  == 1 )
                Pre
                @elseif($type  == 2)
                Construction
                @elseif($type  == 3)
                Post
                @else
                N/A
                @endif Construction Report</a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row x_panel">
    
    <div class="col-md-12">
        <div class="x_content">
            <h3>
             @if(isset($parent->name))
                {{$parent->name}}
            @else
              @if($type  == 1 )
                Pre
                @elseif($type  == 2)
                Construction
                @elseif($type  == 3)
                Post
                @else
                N/A
                @endif Construction Report
            @endif
               </h3>
                
          <table class="table text-center table-striped projects table-bordered">
            <thead>
              <tr>
                <th style="width: 1%">#</th>
                <th style="width:25%;" >Test Name </th>
                <th>No. of sub-activites</th>
                <th style="width:7%;"> Complied (Yes/No/Not Applicable) </th>
                <th style="width:10%;"> Planned Date  </th>
                <th style="width:10%;"> Actual date  </th>
                <th style="width:20%;" >Remarks</th>
                <th style="width:7%;">Documents</th>
                <th style="width:7%;">Photos</th>
              </tr>
            </thead>
        <tbody>
              @if(count($data) > 0)
                @foreach($data as $key => $d)
                  <tr>
                    <td>{{ ++$key }}.</td>
                    <th class="text-center">
                        @if($d->have_child == 1)
                            <a style="color:blue;" href="{{ url('all/sub/tests/'.$moduleType.'/'.$id.'/'.$d['type'].'/'.$d->id) }}">{{ $d->name ?? 'N/A' }}</a>   
                        @else
                            {{ $d->name ?? 'N/A' }}
                        @endif
                    </th>
       
                     <th>
                        @if($d->have_child == 1)
                            {{$d->subtests_count}}
                        @else
                            1
                        @endif
                     </th>

            
                    <th class="text-center" >
                        
                     @if($d->have_child == 1)
                            -

                    @elseif($d->reports)
                            @if($d->reports->status == 1)
                                Yes
                            @elseif($d->reports->status == 2)
                                No
                            @elseif($d->reports->status == 2)
                                Not Applicable
                            @else
                                N/A
                            @endif
                        @else
                            N/A
                        @endif
                    </th>
                     
                    <td>{{ ($d->reports ? $d->reports->planned_date : 'N/A') }}</td>
                    <td>{{  ($d->reports ? $d->reports->actual_date : 'N/A') }}</td>
       

                    <td>
                        @if($d->have_child == 1)
                            -
                        @else
                            {{$d->reports->remark ?? "N/A"}}
                        @endif
                    </td>
                    
                    <td class="text-center">
                        @if(isset($d->reports->document))
                            <a download href="{{$d->reports->document}}" class="btn btn-sm btn-primary" >Document</a>    
                         @elseif($d->have_child == 1)
                            -
                        @else
                            N/A
                        @endif
                    </td>
                    
                    <td class="text-center">
                        @if($d->reports)
                            <a href="{{ url('admin/report/images/'.$moduleType.'/'.$id.'/'.$d->id) }}" class="btn btn-sm btn-danger" > Photos</a>   
                        @elseif($d->have_child == 1)
                            -
                        @else
                            N/A
                        @endif
                   </td>
                   
                  </tr>
                @endforeach
              @endif
            </tbody>
     
          </table>
      </div>
    </div>
    
</div>

@stop


