<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorTransactionController extends Controller
{
    /**
     * Display vendor transactions
     */
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        $query = $vendor->transactions()->with(['order', 'orderItem.product']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        // Calculate totals
        $totals = [
            'current_balance' => $vendor->balance,
            'total_sales' => $vendor->transactions()
                ->where('type', 'sale')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_commission' => $vendor->transactions()
                ->where('type', 'commission')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_withdrawals' => $vendor->transactions()
                ->where('type', 'withdrawal')
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_balance' => $vendor->transactions()
                ->where('status', 'pending')
                ->sum('amount'),
        ];

        return view('vendor.transactions.index', compact('transactions', 'totals'));
    }

    /**
     * Show transaction details
     */
    public function show($id)
    {
        $vendor = Auth::user()->vendor;
        
        $transaction = $vendor->transactions()
            ->with(['order.user', 'orderItem.product'])
            ->findOrFail($id);

        return view('vendor.transactions.show', compact('transaction'));
    }

    /**
     * Request withdrawal
     */
    public function withdrawalRequest()
    {
        $vendor = Auth::user()->vendor;
        
        return view('vendor.transactions.withdrawal', compact('vendor'));
    }

    /**
     * Process withdrawal request
     */
    public function processWithdrawal(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $request->validate([
            'amount' => 'required|numeric|min:50|max:' . $vendor->balance,
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_holder' => 'required|string',
        ]);

        try {
            // Create withdrawal transaction
            $vendor->transactions()->create([
                'type' => 'withdrawal',
                'amount' => -$request->amount,
                'balance_after' => $vendor->balance - $request->amount,
                'description' => 'Withdrawal request - ' . $request->bank_name . ' - ' . $request->account_number,
                'status' => 'pending',
            ]);

            // Update vendor balance
            $vendor->decrement('balance', $request->amount);

            return redirect()
                ->route('vendor.transactions.index')
                ->with('success', 'Withdrawal request submitted successfully! It will be processed within 3-5 business days.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to process withdrawal: ' . $e->getMessage());
        }
    }
}
