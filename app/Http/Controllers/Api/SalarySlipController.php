<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalarySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalarySlipController extends Controller
{
   public function index(Request $request)
{
    $user = $request->user();

    $slips = SalarySlip::where('user_id', $user->id)
                ->orderBy('month', 'desc')
                ->get();

    return response()->json([
        'status' => 'success',
        'data' => $slips,
    ]);
}

public function downloadPdf($id)
{
    $slip = SalarySlip::with('user')->findOrFail($id);

    // Optional: Ensure 'month' is a Carbon object
    $slip->month = \Carbon\Carbon::parse($slip->month);

    $pdf = Pdf::loadView('pdf.salary_slip', compact('slip'));

    return $pdf->download('Salary_Slip_' . $slip->user->name . '_' . $slip->month->format('F_Y') . '.pdf');
}
}
