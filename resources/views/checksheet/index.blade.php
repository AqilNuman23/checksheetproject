@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Checksheets</div>
            <div class="card-header text-end">
                <a href="{{ route('checksheet.create') }}" class="btn btn-primary">Create New Checksheets</a>
                <!-- <a href="{{ route('checksheet.export') }}" class="btn btn-success">Export</a> -->
                <!-- open modal -->
                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                    data-bs-target="#importModal">Import</button>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Checksheets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('checksheet.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <!-- if user admin -->
                            @if (Auth::check() && Auth::user()->is_admin)
                                <label for="supplier_id" class="form-label">Select Supplier</label>
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="" disabled selected>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <label for="qe_id" class="form-label">Select QE</label>
                                <select class="form-select" id="qe_id" name="qe_id" required>
                                    <option value="" disabled selected>Select QE</option>
                                    @foreach($qes as $qe)
                                        <option value="{{ $qe->id }}">{{ $qe->name }}</option>
                                    @endforeach
                                </select>
                                <!-- if user supplier -->
                            @elseif (Auth::check() && Auth::user()->is_supplier)
                                <input type="hidden" name="supplier_id" value="{{ Auth::user()->supplier->id }}">
                                <label for="qe_id" class="form-label">Select QE</label>
                                <select class="form-select" id="qe_id" name="qe_id" required>
                                    <option value="" disabled selected>Select QE</option>
                                    @foreach($qes as $qe)
                                        <option value="{{ $qe->id }}">{{ $qe->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <label for="product_id" class="form-label">Select Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="" disabled selected>Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <label for="import_file" class="form-label">Select File</label>
                            <input type="file" class="form-control" id="import_file" name="import_file" required>
                            <button type="submit" class="btn btn-primary mt-3">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush