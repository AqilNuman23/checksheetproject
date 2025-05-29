<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checksheet;
use App\Models\StatusRecord;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // fetch checksheet by product associated with the current user
        $user = auth()->user();

        // if user is qe
        if ($user->role == 'qe') {
            $checksheets = Checksheet::where('qe_id', $user->id)->get();
        } elseif ($user->role == 'supplier') {
            // if user is supplier
            $checksheets = Checksheet::where('supplier_id', $user->id)->get();
        } else {
            // if user is admin
            $checksheets = Checksheet::all();
        }

        // Aggregate counts by status_record_id
        $statusCounts = Checksheet::select('status_record_id', DB::raw('COUNT(*) as count'))
            ->whereIn('id', $checksheets->pluck('id'))
            ->groupBy('status_record_id')
            ->get();

        // Map status_record_id to status names
        $statusLabels = $statusCounts->map(function ($status) {
            return StatusRecord::STATUS[$status->status_record_id] ?? 'Unknown';
        })->toArray();
        $statusData = $statusCounts->pluck('count')->toArray();

        return view('dashboard', [
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
        ]);
    }
}