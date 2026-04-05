@extends('layouts.app')

@section('content')

<div class="container">
    <h3 class="mb-4">Dashboard</h3>

    <div class="row">

        <!-- Students Card -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-success-subtle">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Students</h5>
                        <h2>{{ $studentCount }}</h2>
                    </div>
                    <i class="bi bi-mortarboard-fill fs-1 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Staff Card -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-success-subtle">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Staff</h5>
                        <h2>{{ $staffCount }}</h2>
                    </div>
                    <i class="bi bi-people-fill fs-1 text-success"></i>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
