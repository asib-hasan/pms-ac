@session ('error')
<div class="alert alert-danger alert-dismissible">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    {{ session('error') }}
</div>
@endif

@session ('success')
<div class="alert alert-success alert-dismissible">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <i class="fa fa-check"></i> {{ session('success') }}
</div>
@endif

@if (session('inval'))
    @foreach (session('inval')->all() as $error)
    <div class="alert alert-danger alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            {{ $error }}
    </div>
    @endforeach
@endif
