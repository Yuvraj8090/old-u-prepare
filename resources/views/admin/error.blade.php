@if(session('error'))
<div class="x_panel">
    <div class="alert alert-danger">
        Error! {{ session('error') }}
    </div>
</div>
@endif

@if(session('success'))
<div class="x_panel">
    <div class="alert alert-success">
        Success! {{ session('success') }}
    </div>
</div>
@endif