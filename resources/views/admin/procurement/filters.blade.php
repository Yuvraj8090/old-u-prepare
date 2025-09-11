<style>
    .col-md-3,.col-md-4,.col-md-2,.col-md-8{
        margin-top:10px;
    }
</style>
<form action="" method="GET">
    <div class="row">
        {{-- <div class="col-md-4">
            <input type="text" placeholder="Search..." name="name" value="{{ request()->name ?? '' }}" class="form-control"  >
        </div> --}}

        @if(request()->segment(2) == "index")
            <div class="col-md-2">
                <select name="department" class="form-control">
                    <option value="">  Departments</option>
                    @if(count($department) > 0)
                        @foreach($department as $dp)
                            <option  value="{{ $dp->id }}" @if(request('department') == $dp->id) selected @endif >{{ $dp->department }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        @endif

        @if(isset($category))
            <div class="col-md-2">
                <select name="category" id="category_id" class="form-control" >
                    <option value=""> Category </option>
                    @if(count($category) > 0)
                        @foreach($category as $cat)
                            <option  value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <select name="subcategory" id="subcategory" class="form-control" >
                    <option value=""> Sub-Category </option>
                </select>
            </div>
        @endif

        <div class="col-md-2">
            <select  name="status" class="form-control">
                <option value="">Status</option>
                <option value="0" @if(request('status') == '0') selected @endif>Yet to Initiate</option>
                <option value="1" @if(request('status') == '1') selected @endif>Pending for contract</option>
                <option value="2" @if(request('status') == '2') selected @endif>Completed</option>
            </select>
        </div>

        @if(request()->segment(2) == "index")
            <div class="col-md-2">
                <select  name="year" class="form-control">
                    <option value="">Approval Year</option>
                    @if(count($years) > 0)
                        @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <select name="completion_year" class="form-control">
                    <option value="">Completion Year</option>
                    @if(count($years) > 0)
                        @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('completion_year') == $ye) selected @endif>{{ $ye }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-8">
                <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                    <i class="fa fa-search" ></i>
                    Filter
                </button>

                <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;margin-to" class="btn btn-danger text-white pull-right">
                    <i class="fa fa-refresh" ></i>
                    Reset
                </a>
            </div>
        @else
            <div class="col-md-2">
                <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                    <i class="fa fa-search" ></i>
                    Filter
                </button>

                <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;margin-to" class="btn btn-danger text-white pull-right">
                    <i class="fa fa-refresh" ></i>
                    Reset
                </a>
            </div>
        @endif
    </div>
</form>
