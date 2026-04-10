<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function users(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $users = User::where('role', '!=', 'klien')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })->paginate($perPage)->appends(request()->query());

        return view('owner.users', compact('users'));
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
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
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
                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
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
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'klien',
            'is_active' => true,
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

        $vendor = Vendor::create([
            'name' => $request->name,
            'address' => $request->address,
            'instagram' => $request->instagram,
            'is_active' => true
        ]);

        $vendor->categories()->attach($request->categories);

        return redirect()->route('owner.vendors')->with('success', 'Vendor successfully added!');
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
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|gte:min_price',
            'details' => 'nullable|string',
        ]);

        $vendor->packages()->create($request->only('name', 'min_price', 'max_price', 'details'));

        return redirect()->back()
            ->with('success', 'Package successfully added!')
            ->with('active_tab', 'packages');
    }

    public function updateVendorPackage(Request $request, \App\Models\VendorPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|gte:min_price',
            'details' => 'nullable|string',
        ]);

        $package->update($request->only('name', 'min_price', 'max_price', 'details'));

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

    public function storeVendorPortfolio(Request $request, Vendor $vendor)
    {
        if ($vendor->portfolios()->count() >= 10) {
            return redirect()->back()
                ->withErrors(['error' => 'Maximum limit of 10 portfolio photos reached for this vendor.'])
                ->with('active_tab', 'portfolios');
        }

        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $remainingSlots = 10 - $vendor->portfolios()->count();
        $imagesToProcess = array_slice($request->file('images'), 0, $remainingSlots);

        foreach ($imagesToProcess as $image) {
            $path = $image->store('portfolios', 'public');

            $vendor->portfolios()->create([
                'title' => pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME),
                'image_path' => $path,
            ]);
        }

        return redirect()->back()
            ->with('success', count($imagesToProcess) . ' Photo(s) successfully uploaded!')
            ->with('active_tab', 'portfolios');
    }

    public function destroyVendorPortfolio(\App\Models\VendorPortfolio $portfolio)
    {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($portfolio->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($portfolio->image_path);
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
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        \App\Models\WeddingPackage::create([
            'name' => $request->name,
            'base_price' => $request->base_price,
            'is_active' => true,
        ]);

        return redirect()->route('owner.wedding_packages')->with('success', 'Event Package successfully added!');
    }

    public function updateWeddingPackage(Request $request, \App\Models\WeddingPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $package->update([
            'name' => $request->name,
            'base_price' => $request->base_price,
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        return redirect()->route('owner.wedding_packages')->with('success', 'Event Package successfully updated!');
    }

    public function manageWeddingPackage(\App\Models\WeddingPackage $package)
    {
        $package->load(['templates.category', 'vendors.packages']);
        $masterCategories = \App\Models\VendorCategory::orderBy('name')->get();
        $masterVendors = \App\Models\Vendor::where('is_active', true)->orderBy('name')->get();

        $minCost = 0;
        $maxCost = 0;

        foreach ($package->templates->where('is_included', true) as $template) {
            $assignedVendors = $package->vendors->where('pivot.vendor_category_id', $template->vendor_category_id);

            if ($assignedVendors->isNotEmpty()) {
                $categoryMin = null;
                $categoryMax = 0;

                foreach ($assignedVendors as $vendor) {
                    if ($vendor->packages->isNotEmpty()) {
                        $vMin = $vendor->packages->min('min_price');
                        $vMax = $vendor->packages->max('max_price');

                        if (is_null($categoryMin) || $vMin < $categoryMin) {
                            $categoryMin = $vMin;
                        }
                        if ($vMax > $categoryMax) {
                            $categoryMax = $vMax;
                        }
                    }
                }

                $minCost += $categoryMin ?? 0;
                $maxCost += $categoryMax;
            }
        }

        return view('owner.wedding_packages.manage', compact('package', 'masterCategories', 'masterVendors', 'minCost', 'maxCost'));
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
            'is_included' => 'required|boolean',
            'vendors' => 'nullable|array',
            'vendors.*' => 'exists:vendors,id',
        ]);

        $template->update([
            'is_included' => filter_var($request->is_included, FILTER_VALIDATE_BOOLEAN)
        ]);

        $package->vendors()->wherePivot('vendor_category_id', $template->vendor_category_id)->detach();

        if ($request->has('vendors') && is_array($request->vendors)) {
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
