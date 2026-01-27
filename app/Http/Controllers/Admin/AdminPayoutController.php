<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorTransaction;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminPayoutController extends Controller
{
    /**
     * Display all withdrawal requests
     */
    public function index(Request $request)
    {
        $query = VendorTransaction::with('vendor.user')
            ->where('type', 'withdrawal');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by vendor
        if ($request->has('vendor') && $request->vendor) {
            $query->where('vendor_id', $request->vendor);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->latest()->paginate(20);
        $vendors = Vendor::where('status', 'approved')->get();

        // Stats
        $stats = [
            'pending' => VendorTransaction::where('type', 'withdrawal')
                ->where('status', 'pending')
                ->count(),
            'pending_amount' => VendorTransaction::where('type', 'withdrawal')
                ->where('status', 'pending')
                ->sum('amount'),
            'completed' => VendorTransaction::where('type', 'withdrawal')
                ->where('status', 'completed')
                ->count(),
            'completed_amount' => VendorTransaction::where('type', 'withdrawal')
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        return view('admin.payouts.index', compact('withdrawals', 'vendors', 'stats'));
    }

    /**
     * Show withdrawal details
     */
    public function show(VendorTransaction $withdrawal)
    {
        if ($withdrawal->type !== 'withdrawal') {
            abort(404);
        }

        $withdrawal->load('vendor.user');

        return view('admin.payouts.show', compact('withdrawal'));
    }

    /**
     * Approve withdrawal
     */
    public function approve(Request $request, VendorTransaction $withdrawal)
    {
        if ($withdrawal->type !== 'withdrawal' || $withdrawal->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Invalid withdrawal request.');
        }

        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $withdrawal->update([
            'status' => 'completed',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'payment_reference' => $request->payment_reference,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('admin.payouts.index')
            ->with('success', 'Withdrawal approved and processed!');
    }

    /**
     * Reject withdrawal
     */
    public function reject(Request $request, VendorTransaction $withdrawal)
    {
        if ($withdrawal->type !== 'withdrawal' || $withdrawal->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Invalid withdrawal request.');
        }

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        // Refund to vendor balance
        $vendor = $withdrawal->vendor;
        $vendor->increment('balance', abs($withdrawal->amount));

        $withdrawal->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'notes' => 'Rejected: ' . $request->rejection_reason,
        ]);

        // Create refund transaction
        $vendor->transactions()->create([
            'type' => 'refund',
            'amount' => abs($withdrawal->amount),
            'balance_after' => $vendor->balance,
            'description' => 'Withdrawal request rejected and refunded',
            'status' => 'completed',
        ]);

        return redirect()
            ->route('admin.payouts.index')
            ->with('success', 'Withdrawal rejected and amount refunded to vendor!');
    }

    /**
     * Bulk process withdrawals
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'withdrawals' => 'required|array',
            'withdrawals.*' => 'exists:vendor_transactions,id',
        ]);

        $processed = 0;
        foreach ($request->withdrawals as $id) {
            $withdrawal = VendorTransaction::find($id);

            if ($withdrawal && $withdrawal->type === 'withdrawal' && $withdrawal->status === 'pending') {
                $withdrawal->update([
                    'status' => 'completed',
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                ]);
                $processed++;
            }
        }

        return redirect()
            ->back()
            ->with('success', "{$processed} withdrawals processed successfully!");
    }
}
