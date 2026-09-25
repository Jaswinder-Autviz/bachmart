@extends('layouts.admin')
@section('title', 'Edit Subscription Plan')
@section('page-title', 'Edit Plan: ' . $subscriptionPlan->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-admin p-4">
            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.subscriptions.plans._form', ['subscriptionPlan' => $subscriptionPlan])

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-sm px-4 rounded-pill text-white fw-600 shadow-sm" style="background:#4F46E5;border:none">
                        <i class="bi bi-check2 me-1"></i>Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
