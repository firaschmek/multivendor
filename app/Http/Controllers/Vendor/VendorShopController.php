<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorShopController extends Controller
{
    /**
     * Show shop settings
     */
    public function edit()
    {
        $vendor = Auth::user()->vendor;
        return view('vendor.shop.edit', compact('vendor'));
    }

    /**
     * Update shop settings
     */
    public function update(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($vendor->logo) {
                    Storage::disk('public')->delete($vendor->logo);
                }
                $vendor->logo = $request->file('logo')->store('vendors/logos', 'public');
            }

            // Handle banner upload
            if ($request->hasFile('banner')) {
                // Delete old banner
                if ($vendor->banner) {
                    Storage::disk('public')->delete($vendor->banner);
                }
                $vendor->banner = $request->file('banner')->store('vendors/banners', 'public');
            }

            // Update vendor info
            $vendor->update([
                'shop_name' => $request->shop_name,
                'shop_name_ar' => $request->shop_name_ar,
                'slug' => Str::slug($request->shop_name),
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'address' => $request->address,
                'city' => $request->city,
            ]);

            // Update user phone if provided
            if ($request->phone) {
                Auth::user()->update(['phone' => $request->phone]);
            }

            return redirect()
                ->route('vendor.shop.edit')
                ->with('success', 'Shop settings updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update shop settings: ' . $e->getMessage());
        }
    }
}
