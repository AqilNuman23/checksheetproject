@extends('layouts.app')
@section('content')
<style>
    label {
        font-weight: bold;
    }
</style>
    <div class="container">
        <div class="card">
            <div class="card-header">Update Checksheet</div>
            <div class="card-body">
                <form action="{{ route('checksheet.update' , $checksheet->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="mb-3 row">
                        <div class="mb-3 col-4">
                            <label for="do_no" class="form-label">D/O No.</label>
                            <input type="text" class="form-control" id="do_no" name="do_no"
                                value="{{ old('do_no', $checksheet->do_no) }}" required>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="part_name" class="form-label">Part Name</label>
                            <input type="text" name="part_name" id="part_name" class="form-control"
                                value="{{ old('part_name', $checksheet->part_name) }}" required>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="part_no" class="form-label">Part No.</label>
                            <input type="text" name="part_no" id="part_no" class="form-control"
                                value="{{ old('part_no', $checksheet->part_no) }}" required>
                        </div>

                    </div>
                    <!-- for part_name, part_no, model, material, grade, colour, insp_date, lot_qty, cavity, gross_net_wt -->
                    <div class="mb-3 row">
                        <div class="mb-3 col-4">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" id="model" class="form-control"
                                value="{{ old('model', $checksheet->model) }}">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="material" class="form-label">Material</label>
                            <input type="text" name="material" id="material" class="form-control"
                                value="{{ old('material', $checksheet->material) }}">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="colour" class="form-label">Colour</label>
                            <input type="text" name="colour" id="colour" class="form-control"
                                value="{{ old('colour', $checksheet->colour) }}">
                        </div>

                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-4">
                            <label for="cavity" class="form-label">Cavity</label>
                            <input type="text" name="cavity" id="cavity" class="form-control"
                                value="{{ old('cavity', $checksheet->cavity) }}">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="grade" class="form-label">Grade</label>
                            <input type="text" name="grade" id="grade" class="form-control"
                                value="{{ old('grade', $checksheet->grade) }}">

                        </div>
                        <div class="mb-3 col-4">
                            <label for="lot_qty" class="form-label">Lot Quantity</label>
                            <input type="number" name="lot_qty" id="lot_qty" class="form-control"
                                value="{{ old('lot_qty', $checksheet->lot_qty) }}" required>
                        </div>

                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-4">
                            <label for="gross_net_wt" class="form-label">Gross/Net Weight</label>
                            <input type="text" name="gross_net_wt" id="gross_net_wt" class="form-control"
                                value="{{ old('gross_net_wt', $checksheet->gross_net_wt) }}">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="insp_date" class="form-label">Inspection Date</label>
                            <input type="date" name="insp_date" id="insp_date" class="form-control"
                                value="{{ old('insp_date', $checksheet->insp_date->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row pt-4">
                        <div class="mb-3 col-3">
                            <label for="qe_id" class="form-label">Select QE</label>
                            <select class="form-select" id="qe_id" name="qe_id" required>
                                <option value="" disabled selected>Select QE</option>
                                @foreach($qes as $qe)
                                    <option value="{{ $qe->id }}" {{ old('qe_id', $checksheet->qe_id) == $qe->id ? 'selected' : '' }}>
                                        {{ $qe->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- if user is admin -->
                        <!-- select supplier from supplier list -->
                        @if (Auth::check() && Auth::user()->is_admin)
                            <div class="mb-3 col-3">
                                <label for="supplier_id" class="form-label">Select Supplier</label>
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="" disabled selected>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $checksheet->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <!-- select product from product list -->
                        <div class="mb-3 col-3">
                            <label for="product_id" class="form-label">Select Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="" disabled selected>Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $checksheet->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br class="mb-3">

                    <label for="file" class="form-label">Upload Checksheets Document</label>
                        @if(isset($checksheet) && $checksheet->document)
                            <p>Current File: <a href="{{ asset('storage/' . $checksheet->document) }}" target="_blank">{{ $checksheet->document }}</a></p><br>
                        @endif
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" {{ !isset($checksheet->document) || !$checksheet->document ? 'required' : '' }}>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#name').on('input', function () {
            var name = $(this).val();
            var slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            $('#slug').val(slug);
        });
    });
</script>