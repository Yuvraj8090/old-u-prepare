<style>
    .col-md-3,.col-md-4,.col-md-2,.col-md-8{
        margin-top:10px;
    }

    .dropdown-toggle::after {
        top: 50%;
        right: 10px;
        position: absolute;
        transform: translateY(-50%);
    }

    .dropdown.districts .dropdown-menu {
        padding: 0;
    }

    .dropdown.districts .dropdown-menu li {
        display: flex;
        padding: 5px 10px;
        align-items: center;
    }

    .dropdown.districts .dropdown-menu li label {
        margin: 0 0 0 5px;
        cursor: pointer;
    }
</style>
<form action="" method="GET" autocomplete="off">
    <div class="row">
        <div class="col-lg-10 col-md-8">

            @if(in_array(auth()->user()->role->level, ['ADMIN', 'ONE']))
            <div class="col-md-2">
                <select name="department" class="form-control">
                    <option value="">Departments</option>
                    @if(count($department) > 0)
                        @foreach($department as $dp)
                            <option  value="{{ $dp->id }}" @if(isset($dept_tfltr) && $dept_tfltr == $dp->id){{ __('selected') }}@elseif(request('department') == $dp->id){{ __('selected') }}@endif >{{ $dp->department }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            @endif

            @if(isset($project_districts) && $project_districts->count())
            <div class="col-md-2">
                <div class="dropdown districts">
                    <button class="m-0 form-control dropdown-toggle text-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Districts
                    </button>
                    <ul class="dropdown-menu w-100">
                        @php $user_districts = auth()->user()->district ? json_decode(auth()->user()->district) : NULL; @endphp
                        @foreach($project_districts as $key => $district)
                        <li>
                            <input id="{{ Str::slug($district->name) }}" type="checkbox" name="project_districts[]" value="{{ $district->name }}" @if($user_districts && in_array($district->name, $user_districts)){{ __('checked') }}@endif />
                            <label for="{{ Str::slug($district->name) }}">{{ $district->name }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(isset($category))
                <div class="col-md-2">
                    <select name="category" id="category_id" class="form-control">
                        <option value=""> Category </option>
                        @if(count($category) > 0)
                            @foreach($category as $cat)
                                <option value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="subcategory" id="subcategory" class="form-control" >
                        <option value="">Sub-Category</option>
                        @if(isset($subcategory) && $subcategory->count())
                            @foreach ($subcategory as $scat)
                                <option value="{{ $scat->name }}" @selected(request('subcategory') == $scat->name)>{{ $scat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif

            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">Status</option>
                    @foreach (App\Helpers\Assistant::getProjectStatus(0, 1) as $key => $status)
                        <option value="{{ $key }}" @selected($key == (request('status') ?? ''))>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="year" class="form-control">
                    <option value="">HPC Approval Year</option>
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
                            <option style="background-color:white;color:black;" value="{{ $ye }}" @if(request('completion_year') == $ye) selected @endif>{{ $ye }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

        </div>

        <div class="col-md-2">
            <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;margin-to" class="btn btn-danger text-white pull-right">
                <i class="fa fa-refresh" ></i>
                Reset
            </a>

            <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning pull-right">
                <i class="fa fa-search" ></i>
                Filter
            </button>
        </div>
    </div>
</form>
