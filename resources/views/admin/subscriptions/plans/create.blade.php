@extends('layouts.admin')
@section('title','New Plan')
@section('page-title','Create Subscription Plan')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card-admin p-4">
    @if($errors->any())<div class="alert alert-danger mb-3"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
        @csrf
        @include('admin.subscriptions.plans._form')
        <div class="d-flex gap-2 justify-content-end mt-4">
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn px-4" style="background:#5a67d8;color:#fff;border:none">Create Plan</button>
        </div>
    </form>
</div>
</div></div>
@endsection
