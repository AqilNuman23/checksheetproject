@extends('layouts.app')
@section('content')
    < class="container">
        <div class="card">
            <div class="card-header">Checksheets Details</div>
            <div class="card-body">
                <h5 class="card-title">Checksheets Name: {{ $checksheet->name }}</h5>
                <p class="card-text">Detail: {{ $checksheet->detail }}</p>
                <p class="card-text">Size: {{ $checksheet->size }}</p>
                <p class="card-text">Dimension: {{ $checksheet->dimension }}</p>
                <p class="card-text">Material: {{ $checksheet->material }}</p>
                <p class="card-text">QE: {{ $checksheet->qe->name }}</p>
                <p class="card-text">Supplier: {{ $checksheet->supplier->name }}</p>
                <p class="card-text">Created At: {{ $checksheet->created_at }}</p>
                <p class="card-text">Status: {{ $checksheet->status_record_id->name }}</p>
                <p class="card-text">File: <a href="{{ asset('storage/' . $checksheet->document_path) }}" target="_blank">Download</a></p>
            </div>
            <div class="card-body">
                @if(Auth::check() && Auth::user()->is_admin)
                    <a href="{{ route('checksheet.edit', $checksheet->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('checksheet.delete', $checksheet->id) }}" class="btn btn-danger">Delete</a>
                @endif
            </div>
        </div>
    </div>
@endsection