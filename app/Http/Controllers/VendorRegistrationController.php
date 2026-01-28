<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorRegistrationController extends Controller
{
    /**
     * Show vendor registration form
     */
    public function create()
    {
        return view('vendor.register');
    }

    /**
     * Handle vendor registration
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'shop_name' => 'required|string|max:255',
            'shop_name_ar' => 'nullable|string|max:255',
            'shop_description' => 'required|string',
            'shop_description_ar' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'vendor',
            ]);

            // Create vendor profile
            Vendor::create([
                'user_id' => $user->id,
                'shop_name' => $request->shop_name,
                'shop_name_ar' => $request->shop_name_ar,
                'slug' => Str::slug($request->shop_name),
                'shop_description' => $request->shop_description,
                'shop_description_ar' => $request->shop_description_ar,
                'phone' => $request->phone,
                'commission_rate' => 10, // Default 10% commission
                'status' => 'pending', // Pending admin approval
                'balance' => 0,
            ]);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'تم التسجيل بنجاح! سيتم مراجعة طلبك من قبل الإدارة قريباً.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء التسجيل: ' . $e->getMessage());
        }
    }
}
