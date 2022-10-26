@if (session('notify'))
    <div class="row">
        <div class="alert alert-success">
            {{ session('notify') }}
        </div>
    </div>
@endif
