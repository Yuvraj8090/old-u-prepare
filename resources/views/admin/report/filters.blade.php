@extends('layouts.admin')

@section('content')
<style>
    .custom-form-control{
        height: 40px;
        border-radius: 5px;
        margin-right: 5px;
        width: 350px;
        padding: 10px;
    }
</style>
<style>
    .col-md-4{
        margin-bottom:10px;
    }
    .col-md-2{
         margin-bottom:10px;
    }
</style>

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        
        <h4> Report Filters
        <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" > 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        </h4>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Report Filters </a></li>
            </ol>
        </nav>
    </div>
</div>


<div class="x_panel">

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content" style="min-height:600px;" >
                    
                     <h3> <i class="fa fa-filter" aria-hidden="true"></i>  Report Filters </h3>
                     <br>
                     <form>
                         
                    <div style="font-size:20px;" class="row">
                        
                        <div class="col-md-12">
                            <b>DEPARTMENT </b> : &nbsp;&nbsp;&nbsp;&nbsp;   <input type="checkbox" name="department[]" value="PMU" />&nbsp;PMU 
                            &nbsp;&nbsp;  &nbsp;<input type="checkbox" name="department[]" value="RWD" />&nbsp;RWD
                            &nbsp;&nbsp;  &nbsp;<input type="checkbox" name="department[]" value="PWD" />&nbsp;PWD
                            &nbsp;&nbsp;  &nbsp;<input type="checkbox" name="department[]" value="FOREST" />&nbsp;FOREST
                            &nbsp;&nbsp;  &nbsp;<input type="checkbox" name="department[]" value="USDMA" />&nbsp;USDMA
                        </div>
                        <br><br>
                        <div class="col-md-6">
                            <b>CATEGORY</b> : 
                            <select name="category" id="category_id" class="form-control" >
                                <option value="">SELECT   </option>
                                <option value="All">All  </option>
                                @if(count($category) > 0)
                                    @foreach($category as $cat)
                                    <option  value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <br><br>
                        <div class="col-md-6">
                            <b>SUB-CATEGORY</b> : 
                            <select name="subcategory" id="subcategory" class="form-control"  >
                              <option value=""> {{ ($filters['subcategory'] != 'All') ? $filters['subcategory'] : 'SELECT CATEGORY'  }} </option>
                            </select>
                        </div>
                         <br><br><br>
                         <div class="col-md-6">
                            <b>DISTRICT</b> : 
                            <select name="district"  class="form-control"  >
                                <option value=""> SELECT  </option>
                                @if(count($districts) > 0)
                                    @foreach($districts as $d)
                                    <option  value="{{ $d->name }}"  >{{ $d->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <br><br>
                    <button type="submit" class="btn btn-lg btn-primary" > <i class="fa fa-file" ></i> &nbsp;  Submit  </button>
                         
                    </form>
                
                </div>
            </div>
        </div>
    </div>

</div>

@stop



@section('script')
<script>
$("#category_id").on("change", function (event) {
    event.preventDefault();
    
    let id = $(this).val();

    $.ajax({
        url: "{{ url('getSubCategory') }}/"+id,
        type: "GET",
        success: function (response) {
            
            if (response) {
                populateSelect('#subcategory', response);
            }
        
        },
        error: function (err) {
            toastr.info("Error! Please Contact Admin.");
        },
    });
});

function populateSelect(selector, data) {
    $(selector).removeAttr('readonly');
    $(selector).removeAttr('disabled');
    
    $(selector).empty(); // Clear existing options
    
    $(selector).append($('<option>', {
        value: '',
        text: 'Select'
    }));
    
    $.each(data, function(index, item) {
        $(selector).append($('<option>', {
            value: item.name,
            text: item.name
        }));
    });
}
</script>
@stop
