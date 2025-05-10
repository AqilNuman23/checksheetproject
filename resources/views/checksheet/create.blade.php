@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Create Checksheets</div>
            <div class="card-body">
                <form action="{{ route('checksheet.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="name" class="form-label">Checksheets Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3 row">
                        <label for="detail" class="form-label">Details</label>
                        <!-- will have size, dimension and material -->
                        <div class="mb-3 col-3">
                            <label for="size" class="form-label">Size</label>
                            <input type="text" name="size" id="size" class="form-control">
                        </div>
                        <div class="mb-3 col-3">
                            <label for="dimension" class="form-label">Dimension</label>
                            <input type="text" name="dimension" id="dimension" class="form-control">
                        </div>
                        <div class="mb-3 col-3">
                            <label for="material" class="form-label">Material</label>
                            <input type="text" name="material" id="material" class="form-control">
                        </div>
                    </div>
                    <!-- select qe from qe list -->
                    <div class="mb-3">
                        <label for="qe_id" class="form-label">Select QE</label>
                        <select class="form-select" id="qe_id" name="qe_id" required>
                            <option value="" disabled selected>Select QE</option>
                            @foreach($qes as $qe)
                                <option value="{{ $qe->id }}">{{ $qe->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- if user is admin -->
                    <!-- select supplier from supplier list -->
                    @if (Auth::check() && Auth::user()->is_admin)
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Select Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id" required>
                            <option value="" disabled selected>Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <!-- select product from product list -->
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="" disabled selected>Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload Checksheets Document</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
                $('#slug').val(slug);
            });
        });
    </script>