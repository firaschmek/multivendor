<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Mail\VendorApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminVendorController extends Controller
{
    /**
     * Display a listing of all vendors
     */
    public function index(Request $request)
    {
        $query = Vendor::with('user');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                  ->orWhere('shop_name_ar', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $vendors = $query->latest()->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Store a newly created vendor in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            // User information
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            
            // Shop information
            'shop_name' => 'required|string|max:255',
            'shop_name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            
            // Business information
            'commission_rate' => 'required|numeric|min:0|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            
            // Images
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            
            // Status
            'status' => 'required|in:pending,approved,suspended',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'vendor',
                'email_verified_at' => now(), // Auto-verify admin-created vendors
            ]);

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            }

            // Handle banner upload
            $bannerPath = null;
            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->store('vendors/banners', 'public');
            }

            // Create vendor record
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'shop_name' => $request->shop_name,
                'shop_name_ar' => $request->shop_name_ar,
                'slug' => Str::slug($request->shop_name),
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'commission_rate' => $request->commission_rate,
                'logo' => $logoPath,
                'banner' => $bannerPath,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country ?? 'Tunisia',
                'status' => $request->status,
                'balance' => 0,
            ]);

            DB::commit();

            // TODO: Send welcome email to vendor with credentials
            // Mail::to($user->email)->send(new VendorWelcomeMail($user, $request->password));

            return redirect()
                ->route('admin.vendors.index')
                ->with('success', 'Vendor created successfully! Credentials have been sent to their email.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create vendor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified vendor
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['user', 'products', 'orders']);
        
        // Calculate statistics
        $stats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('is_active', true)->count(),
            'total_orders' => $vendor->orders()->count(),
            'pending_orders' => $vendor->orders()->where('status', 'pending')->count(),
            'total_sales' => $vendor->orders()->where('status', 'completed')->sum('total_amount'),
            'total_commission' => $vendor->transactions()->sum('platform_commission'),
            'current_balance' => $vendor->balance,
        ];

        return view('admin.vendors.show', compact('vendor', 'stats'));
    }

    /**
     * Show the form for editing the specified vendor
     */
    public function edit(Vendor $vendor)
    {
        $vendor->load('user');
        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor in storage
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            // User information
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($vendor->user_id)],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            
            // Shop information
            'shop_name' => 'required|string|max:255',
            'shop_name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            
            // Business information
            'commission_rate' => 'required|numeric|min:0|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            
            // Images
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            
            // Status
            'status' => 'required|in:pending,approved,suspended',
        ]);

        try {
            DB::beginTransaction();

            // Update user information
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $vendor->user->update($userData);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($vendor->logo) {
                    \Storage::disk('public')->delete($vendor->logo);
                }
                $vendor->logo = $request->file('logo')->store('vendors/logos', 'public');
            }

            // Handle banner upload
            if ($request->hasFile('banner')) {
                // Delete old banner
                if ($vendor->banner) {
                    \Storage::disk('public')->delete($vendor->banner);
                }
                $vendor->banner = $request->file('banner')->store('vendors/banners', 'public');
            }

            // Update vendor information
            $vendor->update([
                'shop_name' => $request->shop_name,
                'shop_name_ar' => $request->shop_name_ar,
                'slug' => Str::slug($request->shop_name),
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'commission_rate' => $request->commission_rate,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.vendors.show', $vendor)
                ->with('success', 'Vendor updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update vendor: ' . $e->getMessage());
        }
    }

    /**
     * Approve a pending vendor
     */
    public function approve(Vendor $vendor)
    {
        $vendor->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Send approval notification email
        $vendor->load('user');
        Mail::to($vendor->user->email)->send(new VendorApprovedMail($vendor));

        return redirect()
            ->back()
            ->with('success', 'تم قبول البائع بنجاح!');
    }

    /**
     * Suspend a vendor
     */
    public function suspend(Vendor $vendor)
    {
        $vendor->update(['status' => 'suspended']);

        // TODO: Send suspension notification email
        // Mail::to($vendor->user->email)->send(new VendorSuspendedMail($vendor));

        return redirect()
            ->back()
            ->with('success', 'Vendor suspended successfully!');
    }

    /**
     * Remove the specified vendor from storage
     */
    public function destroy(Vendor $vendor)
    {
        try {
            DB::beginTransaction();

            // Check if vendor has orders
            if ($vendor->orders()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete vendor with existing orders. Consider suspending instead.');
            }

            // Delete vendor images
            if ($vendor->logo) {
                \Storage::disk('public')->delete($vendor->logo);
            }
            if ($vendor->banner) {
                \Storage::disk('public')->delete($vendor->banner);
            }

            // Delete vendor and user
            $user = $vendor->user;
            $vendor->delete();
            $user->delete();

            DB::commit();

            return redirect()
                ->route('admin.vendors.index')
                ->with('success', 'Vendor deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete vendor: ' . $e->getMessage());
        }
    }
}
