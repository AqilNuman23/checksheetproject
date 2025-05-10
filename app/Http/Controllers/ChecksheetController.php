<?php

namespace App\Http\Controllers;
use App\DataTables\ChecksheetDataTable;
use App\Models\Checksheet;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewChecksheetNotification;
use Illuminate\Support\Facades\Notification;

use Illuminate\Http\Request;

class ChecksheetController extends Controller
{
    public function index(ChecksheetDataTable $dataTable)
    {
        return $dataTable->render('checksheet.index');
    }

    public function show($id)
    {
        $checksheet = Checksheet::findOrFail($id);
        return view('checksheet.show', compact('checksheet'));
    }

    public function create()
    {
        $user = auth()->user();

        $qes = User::where('role', 'qe')->get();
        $suppliers = User::where('role', 'supplier')->get();
        $products = Product::all();

        return view('checksheet.create', compact('user', 'qes', 'products', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Validate and store the checksheet data
        if (auth()->user()->is_admin) {
            $request->validate([
                'qe_id' => 'required|exists:users,id',
                'supplier_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'file' => 'required|file|max:11248',
            ]);

            // combine size, dimension and material to details with json

            $checksheet = new Checksheet();
            $checksheet->qe_id = $request->qe_id;
            $checksheet->supplier_id = $request->supplier_id;
            $checksheet->product_id = $request->product_id;
            $checksheet->details = json_encode([
                'size' => $request->size,
                'dimension' => $request->dimension,
                'material' => $request->material,
            ]);
            $checksheet->status = Status::DRAFT;
            $checksheet->updated_by = auth()->user()->id;
            $checksheet->document_path = $request->file('file')->storeAs('checksheet_files', $request->file('file')->getClientOriginalName(), 'public');
            $checksheet->save();
        } else {
            $request->validate([
                'qe_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'file' => 'required|file|mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation|extensions:pdf,txt,doc,docx,ppt,pptx|max:11248',
            ]);

            $checksheet = new Checksheet();
            $checksheet->qe_id = $request->qe_id;
            $checksheet->supplier_id = auth()->user()->id;
            $checksheet->product_id = $request->product_id;
            $checksheet->details = json_encode([
                'size' => $request->size,
                'dimension' => $request->dimension,
                'material' => $request->material,
            ]);
            $checksheet->updated_by = auth()->user()->id;
            $checksheet->document_path = $request->file('file')->storeAs('checksheet_files', $request->file('file')->getClientOriginalName(), 'public');
            $checksheet->save();
        }
        $qe = User::find($request->qe_id);
        if ($qe) {
            Notification::send($qe, new NewChecksheetNotification($qe, $checksheet));
        }

        // Redirect or return a response
        return redirect()->route('checksheet.index')->with('success', 'Checksheet created successfully.');
    }
}
