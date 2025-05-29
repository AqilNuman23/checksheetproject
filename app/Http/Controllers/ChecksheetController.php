<?php

namespace App\Http\Controllers;
use App\DataTables\ChecksheetDataTable;
use App\Models\Checksheet;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewChecksheetNotification;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Models\StatusRecord;
use Spatie\PdfToText\Pdf;
use App\Models\LogRecordChecksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ChecksheetController extends Controller
{
    public function index(ChecksheetDataTable $dataTable)
    {
        $suppliers = User::where('role', 'supplier')->get();
        $qes = User::where('role', 'qe')->get();
        $products = Product::all();

        return $dataTable->render('checksheet.index', compact('suppliers', 'qes', 'products'));
    }

    public function show($id)
    {
        $checksheet = Checksheet::findOrFail($id);

        // get log records for this checksheet
        $logs = LogRecordChecksheet::where('checksheet_id', $id)
            ->with(['user', 'product', 'checksheet', 'statusRecord'])
            ->get();

        return view('checksheet.show', compact('checksheet', 'logs'));
    }

    public function export()
    {

    }

    // public function getImportData()
    // {
    //     if (auth()->user()->is_admin) {
    //         $suppliers = User::where('role', 'supplier')->get();
    //         $qes = User::where('role', 'qe')->get();
    //     } else {
    //         $qes = User::where('role', 'qe')->get();
    //     }
    //     return view('checksheet.import' , compact('$suppliers', '$qes'));
    // }

    public function import(Request $request)
    {
        //  dd($request->import_file);

        // $text = str_replace("\r", "", $text);
        // $lines = explode("\n", $text);
        // $lines = array_filter($lines, function ($line) {
        //     return trim($line) !== "";
        // });
        // $lines = array_values($lines);


        // Read PDF using pdf-to-text
        $file = $request->file('import_file');
        $text = (new Pdf())
            ->setPdf($file)
            ->text();

        $lines = array_values(array_filter(preg_split("/\r\n|\r|\n/", $text), fn($line) => trim($line) !== ""));

        $data = [
            'part_name' => null,
            'part_no' => null,
            'model' => null,
            'material' => null,
            'grade' => null,
            'do_no' => null,
            'colour' => null,
            'insp_date' => null,
            'lot_qty' => null,
            'cavity' => null,
            'gross_net_wt' => null,
        ];

        $labelMap = [
            'PART NAME' => 'part_name',
            'PART NO' => 'part_no',
            'MODEL' => 'model',
            'MATERIAL' => 'material',
            'GRADE' => 'grade',
            'D/O NO' => 'do_no',
            'COLOUR' => 'colour',
            'INSP DATE' => 'insp_date',
            'LOT QTY' => 'lot_qty',
            'CAVITY' => 'cavity',
            'GROSS NET WT' => 'gross_net_wt',
        ];

        foreach ($lines as $index => $line) {
            foreach ($labelMap as $label => $key) {
                if (str_contains($line, $label)) {
                    $nextLineIndex = $index + 1;
                    if (isset($lines[$nextLineIndex])) {
                        $data[$key] = trim($lines[$nextLineIndex]);
                    }
                    break;
                }
            }
        }
        dd($data);

        // Checksheet::create([
        //     'qe_id' => $request->qe_id,
        //     'supplier_id' => $request->supplier_id,
        //     'product_id' => $request->product_id,
        //     'part_name' => $data['part_name'],
        //     'part_no' => $data['part_no'],
        //     'model' => $data['model'],
        //     'material' => $data['material'],
        //     'grade' => $data['grade'],
        //     'do_no' => $data['do_no'],
        //     'colour' => $data['colour'],
        //     'insp_date' => $data['insp_date'],
        //     'lot_qty' => $data['lot_qty'],
        //     'cavity' => $data['cavity'],
        //     'gross_net_wt' => $data['gross_net_wt'],
        //     'status_record_id' => StatusRecord::DRAFT,
        //     'document' => $file->storeAs('checksheet_files', $file->getClientOriginalName(), 'public'),
        // ]);

        // return to create page with success message
        return redirect()->route('checksheet.create')->with('success', 'Checksheet imported successfully.');

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
            $checksheet->status = StatusRecord::DRAFT;
            $checksheet->updated_by = auth()->user()->id;
            $checksheet->document = $request->file('file')->storeAs('checksheet_files', $request->file('file')->getClientOriginalName(), 'public');
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
            $checksheet->document = $request->file('file')->storeAs('checksheet_files', $request->file('file')->getClientOriginalName(), 'public');
            $checksheet->save();
        }
        $qe = User::find($request->qe_id);
        if ($qe) {
            Notification::send($qe, new NewChecksheetNotification($qe, $checksheet));
        }

        // Redirect or return a response
        return redirect()->route('checksheet.index')->with('success', 'Checksheet created successfully.');
    }

    public function edit($id)
    {
        $checksheet = Checksheet::findOrFail($id);
        $user = auth()->user();

        $qes = User::where('role', 'qe')->get();
        $suppliers = User::where('role', 'supplier')->get();
        $products = Product::all();

        return view('checksheet.edit', compact('checksheet', 'qes', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {

        $checksheet = Checksheet::findOrFail($id);
        // dd(StatusRecord::DRAFT);

        // dd($request->insp_date);

        // Define the fields to compare (adjust based on your Checksheet model)
        $compare = [
            'qe_id' => $request->qe_id,
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'do_no' => $request->do_no,
            'part_name' => $request->part_name,
            'part_no' => $request->part_no,
            'model' => $request->model,
            'material' => $request->material,
            'colour' => $request->colour,
            'insp_date' => $request->insp_date,
            'lot_qty' => $request->lot_qty,
            'cavity' => $request->cavity,
            'grade' => $request->grade,
            'gross_net_wt' => $request->gross_net_wt,
        ];

        $checksheet->fill($compare);

        // dd($compare);

        // dirty true or false
        // dd([
        //     'current' => $checksheet->getOriginal('insp_date'),
        //     'new' => $compare['insp_date'],
        //     'isDirty' => $checksheet->isDirty('insp_date'),
        // ]);

        if (!$checksheet->isDirty()) {
            return redirect()->route('checksheet.show', $id);
        }

        // Update the checksheet if there are changes
        $checksheet->status_record_id = StatusRecord::DRAFT;
        $checksheet->update();

        // create log record using LogRecord orm
        LogRecordChecksheet::create([
            'user_id' => Auth::user()->id,
            'checksheet_id' => $id,
            'status_record_id' => StatusRecord::DRAFT,
        ]);

        return redirect()->route('checksheet.show', $id)->with('success', 'Checksheet updated successfully.');
    }
}