@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Checksheets</div>
            <div class="card-header text-end">
                <a href="{{ route('checksheet.create') }}" class="btn btn-primary">Create New Checksheets</a>
                <a href="{{ route('checksheet.export') }}" class="btn btn-success">Export</a>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush