<form action="" method="GET" autocomplete="off">
    <div class="row">
        <div class="col-md-2">
           <select name="department"  class="form-control" >
                <option value="">DEPARTMENT   </option>
                @if(count($department) > 0)
                    @foreach($department as $a)
                    <option  value="{{ $a->id }}"  @if(request('department') == $a->id) selected @endif >{{ $a->department }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="col-md-2">
            <select name="district"  class="form-control"  >
                <option value=""> DISTRICT  </option>
                @if(count($districts) > 0)
                    @foreach($districts as $d)
                    <option  value="{{ $d->name }}"  @if(request('district') == $d->name) selected @endif>{{ $d->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        @if(!in_array(request()->segment('3'),['work-report','goods-report','consultancy-report','others-report','environment-social-report']))
            <div class="col-md-2">
                <select name="category" id="category_id" class="form-control" >
                    <option value="">CATEGORY</option>
                    @if(count($category) > 0)
                        @foreach($category as $cat)
                        <option  value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <select name="subcategory" id="subcategory" class="form-control"  >
                    <option value="">SUBCATEGORY</option>
                    @if($subcategory->count())
                        @foreach($subcategory as $scat)
                            <option value="{{ $scat->name }}" @selected(request('subcategory') == $scat->name)>{{ $scat->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        @endif

        <div class="col-md-4">
            <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                <i class="fa fa-search" ></i>
                Filter
            </button>

            <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                <i class="fa fa-refresh" ></i>
                Reset
            </a>

            <button type="button" onclick="exportToPDF()" style="border-radius:5px;margin-left:10px;" class="btn btn-md btn-primary pull-right" >
                <i class="fa fa-file" ></i> &nbsp; Export in PDF
            </button>

        </div>

    </div>
</form>