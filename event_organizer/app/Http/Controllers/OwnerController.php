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

        $users = User::when($search, function ($query, $search) {
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
            'role' => 'required|in:owner,pl,klien,crew_rsvp,crew_eo',
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
                'role' => 'required|in:owner,pl,klien,crew_rsvp,crew_eo',
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

    public function vendors(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $vendors = Vendor::with('categories')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('categories', function ($qCat) use ($search) {
                            $qCat->where('name', 'like', "%{$search}%");
                        });
                });
            })
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
        ]);

        $vendor = Vendor::create([
            'name' => $request->name,
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
        ]);

        $vendor->update([
            'name' => $request->name,
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
}
