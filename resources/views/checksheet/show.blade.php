@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Checksheet Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="do_no" class="form-label">D/O Number</strong>
                            <p class="form-control">{{ $checksheet->do_no ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="part_name" class="form-label">Part Name</strong>
                            <p class="form-control">{{ $checksheet->part_name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="part_no" class="form-label">Part Number</strong>
                            <p class="form-control">{{ $checksheet->part_no ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="model" class="form-label">Model</strong>
                            <p class="form-control">{{ $checksheet->model ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="material" class="form-label">Material</strong>
                            <p class="form-control">{{ $checksheet->material ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="colour" class="form-label">Colour</strong>
                            <p class="form-control">{{ $checksheet->colour ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="grade" class="form-label">Grade</strong>
                            <p class="form-control">{{ $checksheet->grade ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="cavity" class="form-label">Cavity</strong>
                            <p class="form-control">{{ $checksheet->cavity ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="lot_qty" class="form-label">Lot Quantity</strong>
                            <p class="form-control">{{ $checksheet->lot_qty ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="gross_net_wt" class="form-label">Gross/Net Weight</strong>
                            <p class="form-control">{{ $checksheet->gross_net_wt ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="insp_date" class="form-label">Inspection Date</strong>
                            <p class="form-control">{{ $checksheet->insp_date->format('d/m/Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="mt-4">
                <h5 class="card-title pt-3"></h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="supplier" class="form-label">Supplier</strong>
                            <p class="form-control">{{ $checksheet->supplier->name . ' from ' . ($checksheet->supplier->company->name ?? '-') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="qe" class="form-label">Quality Engineer</strong>
                            <p class="form-control">{{ $checksheet->qe->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="mt-4">
                <h5 class="card-title pt-3">Product Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="product" class="form-label">Product</strong>
                            <p class="form-control">{{ $checksheet->product->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="file" class="form-label">File</strong>
                            <p class="form-control">
                                @if($checksheet->document)
                                    <a href="{{ asset('storage/' . $checksheet->document) }}" target="_blank">{{ basename($checksheet->document) }}</a>
                                @else
                                    No document uploaded.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="mt-4">
                <h5 class="card-title pt-3">Checksheet Status</h5>
                <!-- appear a log of status for the checksheet with a date and latest edit by (user name) -->
                @if ($logs->isEmpty())
                    <p class="text-muted">No status logs available.</p>
                @endif
                <ul class="list-group">
                    @foreach($logs as $log)
                        <li class="list-group-item">
                            <strong>{{ $log->created_at->format('d/m/Y H:i:s') }}</strong> -
                            {{ $log->statusRecord->getStatusName() }}
                            @if($log->user)
                                by <em>{{ $log->user->name }}</em>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if(Auth::check() && Auth::user()->is_admin)
                    <div class="mt-4">
                        <a href="{{ route('checksheet.edit', $checksheet->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('checksheet.delete', $checksheet->id) }}"
                            class="btn btn-danger float-right">Delete</a>
                        <a href="{{ route('checksheet.index') }}" class="btn btn-secondary float-right">Back to List</a>
                    </div>
                @endif
            </div>
        </div>
@endsection