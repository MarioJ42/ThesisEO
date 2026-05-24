<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OwnerController extends Controller
{
    public function users(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $user = \Illuminate\Support\Facades\Auth::user();
        $events = \App\Models\Event::all();

        $users = User::where('role', '!=', 'klien')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })->paginate($perPage)->appends(request()->query());

        return view('owner.users', compact('users', 'user', 'events'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,pl,crew_rsvp,crew_eo',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'must_change_password' => true,
        ]);

        return redirect()->route('owner.users')->with('success', 'User account successfully added!');
    }

    public function updateUser(Request $request, User $user)
    {
        if (\Illuminate\Support\Facades\Auth::id() === $user->id) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'required|string|max:20',
                'role' => 'required|in:owner,pl,crew_rsvp,crew_eo',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
        } else {
            $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $user->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        $user->save();

        return redirect()->route('owner.users')->with('success', 'User account successfully updated!');
    }

    public function clients(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $clients = User::where('role', 'klien')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })->paginate($perPage)->appends(request()->query());

        return view('owner.clients', compact('clients'));
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'klien',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        return redirect()->route('owner.clients')->with('success', 'Client account successfully added!');
    }

    public function updateClient(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        $user->save();

        return redirect()->route('owner.clients')->with('success', 'Client account successfully updated!');
    }

    public function vendors(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $vendors = Vendor::with('categories')
            ->whereNotIn('name', ['Fenix EO', 'Pribadi'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('categories', function ($qCat) use ($search) {
                            $qCat->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage)
            ->appends(request()->query());

        $masterCategories = \App\Models\VendorCategory::orderBy('name', 'asc')->get();

        return view('owner.vendors', compact('vendors', 'masterCategories'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vendors,name',
            'categories' => 'required|array',
            'categories.*' => 'exists:vendor_categories,id',
            'address' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
        ]);

        $vendor = \App\Models\Vendor::create([
            'name' => $request->name,
            'address' => $request->address,
            'instagram' => $request->instagram,
            'is_active' => true
        ]);

        $vendor->categories()->attach($request->categories);
        return redirect()->route('owner.vendors.manage', $vendor->id)
            ->with('success', 'Vendor successfully added! You can now add PIC, packages, and portfolios.');
    }

    public function updateVendor(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vendors,name,' . $vendor->id,
            'categories' => 'required|array',
            'categories.*' => 'exists:vendor_categories,id',
            'is_active' => 'required|boolean',
            'address' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
        ]);

        $vendor->update([
            'name' => $request->name,
            'address' => $request->address,
            'instagram' => $request->instagram,
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        $vendor->categories()->sync($request->categories);

        return redirect()->route('owner.vendors')->with('success', 'Vendor successfully updated!');
    }

    public function manageVendor(Vendor $vendor)
    {
        $vendor->load(['categories', 'contacts', 'packages', 'portfolios']);

        return view('owner.vendors.manage', compact('vendor'));
    }

    public function storeVendorContact(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->is_primary) {
            $vendor->contacts()->update(['is_primary' => false]);
        }

        $vendor->contacts()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'is_primary' => $request->boolean('is_primary'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->back()
            ->with('success', 'PIC Contact successfully added!')
            ->with('active_tab', 'contacts');
    }

    public function updateVendorContact(Request $request, \App\Models\VendorContact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->is_primary) {
            $contact->vendor->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }

        $contact->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'is_primary' => $request->boolean('is_primary'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->back()
            ->with('success', 'PIC Contact successfully updated!')
            ->with('active_tab', 'contacts');
    }

    public function destroyVendorContact(\App\Models\VendorContact $contact)
    {
        $contact->delete();
        return redirect()->back()
            ->with('success', 'PIC Contact successfully deleted!')
            ->with('active_tab', 'contacts');
    }

    public function storeVendorPackage(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'net_price' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);

        $vendor->packages()->create($request->only('name', 'price', 'net_price', 'details'));

        return redirect()->back()
            ->with('success', 'Package successfully added!')
            ->with('active_tab', 'packages');
    }

    public function updateVendorPackage(Request $request, \App\Models\VendorPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'net_price' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);

        $package->update($request->only('name', 'price', 'net_price', 'details'));

        return redirect()->back()
            ->with('success', 'Package successfully updated!')
            ->with('active_tab', 'packages');
    }

    public function destroyVendorPackage(\App\Models\VendorPackage $package)
    {
        $package->delete();

        return redirect()->back()
            ->with('success', 'Package successfully deleted!')
            ->with('active_tab', 'packages');
    }

    public function storeVendorPortfolio(Request $request, \App\Models\Vendor $vendor)
    {
        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('vendor_portfolios', 'public');

                DB::table('vendor_portfolios')->insert([
                    'vendor_id' => $vendor->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'image_path' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Photos and description uploaded successfully!');
    }

    public function destroyVendorPortfolio(\App\Models\VendorPortfolio $portfolio)
    {
        if (Storage::disk('public')->exists($portfolio->image_path)) {
            Storage::disk('public')->delete($portfolio->image_path);
        }

        $portfolio->delete();

        return redirect()->back()
            ->with('success', 'Photo successfully deleted!')
            ->with('active_tab', 'portfolios');
    }

    public function weddingPackages(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $packages = \App\Models\WeddingPackage::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('base_price', 'asc')
            ->paginate($perPage)
            ->appends(request()->query());

        return view('owner.wedding_packages', compact('packages'));
    }

    public function storeWeddingPackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wedding_packages,name',
        ]);

        $package = \App\Models\WeddingPackage::create([
            'name' => $request->name,
            'base_price' => 0,
            'eo_fee' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('owner.wedding_packages.manage', $package->id)
            ->with('success', 'Event Package successfully added! You can now arrange the templates.');
    }

    public function updateWeddingPackage(Request $request, \App\Models\WeddingPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wedding_packages,name,' . $package->id,
            'is_active' => 'required|boolean',
        ]);

        $package->update([
            'name' => $request->name,
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        return redirect()->route('owner.wedding_packages')->with('success', 'Event Package successfully updated!');
    }

    public function updatePackagePrice(Request $request, \App\Models\WeddingPackage $package)
    {
        $request->validate([
            'base_price' => 'required|numeric|min:0',
            'eo_fee' => 'required|numeric|min:0',
        ]);

        $package->update([
            'base_price' => $request->base_price,
            'eo_fee' => $request->eo_fee,
        ]);

        return redirect()->back()
            ->with('success', 'Selling Price successfully updated!')
            ->with('active_tab', 'assignment');
    }

    public function manageWeddingPackage(\App\Models\WeddingPackage $package)
    {
        $package->load(['templates.category', 'vendors.packages']);
        $masterCategories = \App\Models\VendorCategory::orderBy('name')->get();
        $masterVendors = \App\Models\Vendor::where('is_active', true)->orderBy('name')->get();

        $minCost = 0;

        $includedCategoryIds = $package->templates
            ->where('is_included', true)
            ->pluck('vendor_category_id')
            ->unique();

        foreach ($includedCategoryIds as $categoryId) {
            $assignedVendors = $package->vendors->where('pivot.vendor_category_id', $categoryId);

            if ($assignedVendors->isNotEmpty()) {
                $categoryMin = null;

                foreach ($assignedVendors as $vendor) {
                    $validPackages = $vendor->packages->where('vendor_category_id', $categoryId);

                    if ($validPackages->isNotEmpty()) {
                        $vMin = $validPackages->min('price');

                        if (is_null($categoryMin) || $vMin < $categoryMin) {
                            $categoryMin = $vMin;
                        }
                    }
                }

                $minCost += $categoryMin ?? 0;
            }
        }

        return view('owner.wedding_packages.manage', compact('package', 'masterCategories', 'masterVendors', 'minCost'));
    }

    public function storePackageTemplate(Request $request, \App\Models\WeddingPackage $package)
    {
        $request->validate([
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'session' => 'required|string',
        ]);

        $package->templates()->create([
            'vendor_category_id' => $request->vendor_category_id,
            'session' => $request->session,
            'role_detail' => '-',
            'is_included' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Category successfully added to template!')
            ->with('active_tab', 'template');
    }

    public function destroyPackageTemplate(\App\Models\WeddingPackage $package, \App\Models\PackageTemplate $template)
    {
        $package->vendors()->wherePivot('vendor_category_id', $template->vendor_category_id)->detach();

        $template->delete();

        return redirect()->back()
            ->with('success', 'Category successfully removed from template!')
            ->with('active_tab', 'template');
    }

    public function assignVendorToTemplate(Request $request, \App\Models\WeddingPackage $package, \App\Models\PackageTemplate $template)
    {
        $request->validate([
            'vendors' => 'nullable|array',
            'vendors.*' => 'exists:vendors,id',
        ]);

        $hasVendors = $request->has('vendors') && is_array($request->vendors) && count($request->vendors) > 0;

        $template->update([
            'is_included' => $hasVendors
        ]);

        $package->vendors()->wherePivot('vendor_category_id', $template->vendor_category_id)->detach();

        if ($hasVendors) {
            foreach ($request->vendors as $vendorId) {
                $package->vendors()->attach($vendorId, [
                    'vendor_category_id' => $template->vendor_category_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Vendor assignment successfully updated!')
            ->with('active_tab', 'assignment');
    }
}
