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
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">Cancel</a>
            <button type="submit" class="btn btn-sm px-4 rounded-pill text-white fw-600 shadow-sm" style="background:#4F46E5;border:none">Create Plan</button>
        </div>
    </form>
</div>
</div></div>
@endsection
