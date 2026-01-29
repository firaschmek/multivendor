<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Mail\VendorRegistrationReceivedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VendorRegistrationController extends Controller
{
    /**
     * Store a new vendor application
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول أولاً للتسجيل كبائع');
        }

        // Check if user is already a vendor
        $user = Auth::user();

        if ($user->role === 'vendor') {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'أنت مسجل بالفعل كبائع');
        }

        // Check if user has a pending vendor application
        $existingVendor = Vendor::where('user_id', $user->id)->first();
        if ($existingVendor) {
            if ($existingVendor->status === 'pending') {
                return redirect()->back()
                    ->with('error', 'لديك طلب قيد المراجعة بالفعل');
            }
            if ($existingVendor->status === 'suspended') {
                return redirect()->back()
                    ->with('error', 'تم تعليق حسابك كبائع. يرجى التواصل مع الدعم');
            }
        }

        // Validate the request
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_name_ar' => 'required|string|max:255',
            'description_ar' => 'nullable|string|max:2000',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address_ar' => 'required|string|max:500',
            'tax_number' => 'nullable|string|max:50',
            'business_license' => 'nullable|string|max:50',
            'terms' => 'required|accepted',
        ], [
            'shop_name.required' => 'اسم المتجر مطلوب',
            'shop_name_ar.required' => 'اسم المتجر بالعربية مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'address_ar.required' => 'العنوان مطلوب',
            'terms.required' => 'يجب الموافقة على الشروط والأحكام',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
        ]);

        // Create the vendor application
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'shop_name' => $validated['shop_name'],
            'shop_name_ar' => $validated['shop_name_ar'],
            'slug' => Str::slug($validated['shop_name']),
            'description_ar' => $validated['description_ar'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address_ar' => $validated['address_ar'],
            'tax_number' => $validated['tax_number'] ?? null,
            'business_license' => $validated['business_license'] ?? null,
            'commission_rate' => 10.00, // Default commission rate
            'balance' => 0,
            'status' => 'pending',
        ]);

        // Update user role to vendor (will need admin approval to access vendor panel)
        $user->update(['role' => 'vendor']);

        // Send registration confirmation email
        $vendor->load('user');
        Mail::to($user->email)->send(new VendorRegistrationReceivedMail($vendor));

        return redirect()->route('home')
            ->with('success', 'تم إرسال طلبك بنجاح! سنراجعه وسنتواصل معك خلال 24 ساعة.');
    }
}
