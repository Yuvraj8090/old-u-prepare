@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              @include('admin.include.backButton')

    <h4>Office Expenditure</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">Office Expenditure</a></li>
      </ol>
    </nav>
  </div>
</div>



<div class="row x_panel">
  <div class="col-md-12">


    <div class="x_content">
      <form action="" method="GET">


        <select class="form-control col-md-3" name="year">
          <option value="">SELECT YEAR</option>
          @if(count($years) > 0)
              @foreach($years as $year)
                <option value="{{$year}}" {{ request()->year == $year ? 'selected' : '' }}>{{$year}}</option>
              @endforeach
          @endif
        </select>
        
         <select class="form-control col-md-3" name="quarter">
            <option value="">SELECT QUARTER</option>
            <option value="1" {{ request()->quarter == '1' ? 'selected' : '' }}>JANUARY - MARCH</option>
            <option value="2" {{ request()->quarter == '2' ? 'selected' : '' }}>APRIL - JUNE</option>
            <option value="3" {{ request()->quarter == '3' ? 'selected' : '' }}>JULY - SEPEMBER</option>
             <option value="4" {{ request()->quarter == '4' ? 'selected' : '' }}>OCTOMBER - DECEMBER</option>
        </select>

        <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
          <i class="fa fa-search"></i>
          Filter
        </button>

        <a href="{{ url('finance/index') }}" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
          <i class="fa fa-refresh"></i>
          Reset
        </a>

        <button type="button" class="btn btn-md btn-primary">TOTAL EXPENDITURE : {{ formatIndianNumber($data->sum('total_exp')) }}</button>

        <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="pull-right btn btn-success text-white">+ Add expenses</button>



      </form>
    </div>
    <div class="x_content">
      <h5>Note:- Values in INR</h5>
      <table class="table table-striped projects table-bordered">
        <thead>
          <tr>
            <th style="width: 1%">#</th>
            <th>Year </th>
            <th>Quarter </th>
            <th>Office Equipment Exp  </th>
            <th>Electricity Exp </th>
            <th>Transport Exp  </th>
            <th>Salaries Exp   </th>
            <th>Rent Exp   </th>
            <th> Miscellaneous Exp  </th>
            <th>Total Exp </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if(count($data) > 0)

          @foreach($data as $key => $d)
          
          <tr>
            <td>{{ $data->firstItem() + $key }}</td>
            <th> <button class="btn btn-sucess btnb-md"> {{ $d->year}} </button> </th>
            @if($d->quarter == 1)
                <th> <button class="btn btn-sucess btnb-md"> JAN-MAR</button> </th>
            @elseif($d->quarter == 2)
                <th> <button class="btn btn-sucess btnb-md"> APR-JUN </button> </th>
            @elseif($d->quarter == 3)
                <th> <button class="btn btn-sucess btnb-md"> JULY-SEP </button> </th>
            @elseif($d->quarter == 4)
                <th> <button class="btn btn-sucess btnb-md"> OCT-DEC</button> </th>
            @endif
     
            <td>{{ formatIndianNumber($d->office_equipment_exp) }}   </td>
            <td>{{ formatIndianNumber($d->electricty_exp) }} </td>
            <td>{{ formatIndianNumber($d->transport_exp) }} </td>
            <td>{{ formatIndianNumber($d->salaries_exp) }} </td>
            <td>{{ formatIndianNumber($d->rent_exp) }}</td>
            <td>{{ formatIndianNumber($d->miscelleneous_exp) }} </td>
            <td>{{ formatIndianNumber($d->total_exp) }} </td>
            <td>
              <a href="#" data-url="{{ route('finance.edit',$d->id) }}" data-edit="{{ route('finance.update.new',$d->id) }}" class="btn btn-sm btn-info edit-button">
                <i class="fa fa-pencil"></i> Edit
              </a>
            </td>
          </tr>
          
          @endforeach
          <tfooter>
            <tr style="font-size:16px;border:3px solid black !important;">
              <th colspan="3"> <span class="pull-right"  >TOTAL EXPENDITURE</span></th>
              <th>{{ formatIndianNumber($data->sum('office_equipment_exp')) }}</th>
              <th>{{ formatIndianNumber($data->sum('electricty_exp')) }}</th>
              <th>{{ formatIndianNumber($data->sum('transport_exp')) }}</th>
              <th>{{ formatIndianNumber($data->sum('salaries_exp')) }}</th>
              <th>{{ formatIndianNumber($data->sum('rent_exp')) }}</th>
              <th>{{ formatIndianNumber($data->sum('miscelleneous_exp')) }}</th>
              <th>{{ formatIndianNumber($data->sum('total_exp')) }}</th>
              <th></th>
            </tr>
          </tfooter>
          @else
          <tr>
            <td colspan="11">
              <center> NO DATA FOUND </center>
            </td>
          </tr>
          @endif
        </tbody>
      </table>
      {{ $data->links() }}
    </div>
  </div>
</div>
</div>

@stop


@section('modal')
<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Expenditure</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajax-form" data-method="POST" data-action="{{ route('finance.store') }}">
          @csrf

          <div class="form-row">

            <div class="form-group col-md-6">
              <label for="year">Year</label>
              <select class="form-control" name="year">
                <option value="">SELECT YEAR</option>
                    @if(count($years) > 0)
                    @foreach($years as $year)
                    <option value="{{$year}}">{{$year}}</option>
                    @endforeach
                    @endif
              </select>
                <span class="error" id="error-year"></span>
            </div>

            <div class="form-group col-md-6">
              <label for="year">Quarter</label>
              <select class="form-control" name="quarter">
                <option value="">SELECT YEAR</option>
                <option value="1">JANUARY - MARCH</option>
                <option value="2">APRIL - JUNE</option>
                <option value="3">JULY - SEPEMBER</option>
                <option value="4">OCTOBER - DECEMBER</option>
              </select>
                <span class="error" id="error-quarter"></span>
            </div>

          </div>

          <div class="form-group">
            <label for="officeEquipment">Expenditure: Office Equipment (In rupees)</label>
            <input type="number" name="office_equipment_exp" class="form-control" id="officeEquipment" value="0" placeholder="Enter in Rupees..">
            <span class="error" id="error-office_equipment_exp"></span>
          </div>

          <div class="form-group">
            <label for="electricity">Expenditure: Electricity (In rupees)</label>
            <input type="number" name="electricity" class="form-control" id="electricity" value="0" placeholder="Enter in Rupees..">
            <span class="error" id="error-electricity"></span>
          </div>

          <div class="form-group">
            <label for="transport">Expenditure: Transport (In rupees)</label>
            <input type="number" name="transport" class="form-control" id="transport" value="0" placeholder="Enter in Rupees..">
            <span class="error" id="error-transport"></span>
          </div>

          <div class="form-group">
            <label for="salaries">Expenditure: Salaries (In rupees)</label>
            <input type="number" name="salaries" class="form-control" id="salaries" value="0" placeholder="Enter in Rupees..">
            <span class="error" id="error-salaries"></span>
          </div>

          
          <div class="form-group">
            <label for="salaries">Expenditure: Rent (In rupees)</label>
            <input type="number" name="rent_expense" class="form-control" id="salaries" value="0" data-key="rent_exp" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-rent_expense"></span>
          </div>


          <div class="form-group">
            <label for="miscellaneous">Expenditure: Miscellaneous (In rupees)</label>
            <input type="number" name="miscellaneous" class="form-control" id="miscellaneous" value="0" placeholder="Enter in Rupees..">
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="editmodal modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Expenditure :</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editform" class="ajax-form" data-method="POST" data-action="">
          @csrf

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="month">Year</label>
              <input type="text" class="form-control" id="month" data-key="year" readonly>
              <span class="error" id="error-edit-month_year"></span>
            </div>

            <div class="form-group col-md-6">
              <label for="month">Month</label>
              <input type="text" class="form-control"  data-key="QuaterName" readonly>
              <span class="error" id="error-edit-QuaterName"></span>
            </div>
          </div>

          <div class="form-group">
            <label for="officeEquipment">Expenditure: Office Equipment (In rupees)</label>
            <input type="number" name="office_equipment_exp" class="form-control" id="officeEquipment" data-key="office_equipment_exp" value="0" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-office_equipment_exp"></span>
          </div>

          <div class="form-group">
            <label for="electricity">Expenditure: Electricity (In rupees)</label>
            <input type="number" name="electricity" class="form-control" id="electricity" value="0" data-key="electricty_exp" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-electricity"></span>
          </div>

          <div class="form-group">
            <label for="transport">Expenditure: Transport (In rupees)</label>
            <input type="text" name="transport" class="form-control" id="transport" value="0" data-key="transport_exp" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-transport"></span>
          </div>

          <div class="form-group">
            <label for="salaries">Expenditure: Salaries (In rupees)</label>
            <input type="number" name="salaries" class="form-control" id="salaries" value="0" data-key="salaries_exp" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-salaries"></span>
          </div>

          <div class="form-group">
            <label for="salaries">Expenditure: Rent (In rupees)</label>
            <input type="number" name="rent_expense" class="form-control" id="salaries" value="0" data-key="rent_exp" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-rent_expense"></span>
          </div>

          <div class="form-group">
            <label for="miscellaneous">Expenditure: Miscellaneous (In rupees)</label>
            <input type="number" name="miscellaneous" class="form-control" id="miscellaneous" value="0" data-key="miscelleneous_exp" placeholder="Enter in Rupees..">
            <span class="error" id="error-edit-miscellaneous"></span>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>
@stop