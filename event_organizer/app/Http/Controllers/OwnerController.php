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

        $vendors = Vendor::with(['categories', 'contacts'])
            ->when($search, function ($query, $search) {
                // Kita bungkus dalam 'where' closure agar kondisi 'or' tidak mengganggu query utama
                return $query->where(function ($q) use ($search) {
                    // Cari berdasarkan Nama Vendor
                    $q->where('name', 'like', "%{$search}%")
                        // ATAU cari ke dalam tabel categories
                        ->orWhereHas('categories', function ($qCat) use ($search) {
                            $qCat->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate($perPage)
            ->appends(request()->query());

        return view('owner.vendors', compact('vendors'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categories' => 'required|string', // Format: MUA, Venue, Catering
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'required|string|max:20',
        ]);

        // 1. Simpan Vendor
        $vendor = Vendor::create(['name' => $request->name]);

        // 2. Simpan Categories (Pisahkan koma menjadi array)
        $categories = array_map('trim', explode(',', $request->categories));
        foreach ($categories as $cat) {
            $vendor->categories()->create(['name' => $cat]);
        }

        // 3. Simpan Contact
        $vendor->contacts()->create([
            'name' => $request->pic_name,
            'phone' => $request->pic_phone,
        ]);

        return redirect()->route('owner.vendors')->with('success', 'Vendor successfully added!');
    }

    public function updateVendor(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categories' => 'required|string',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'required|string|max:20',
        ]);

        // 1. Update Nama Vendor
        $vendor->update(['name' => $request->name]);

        // 2. Update Categories (Hapus lama, isi baru)
        $vendor->categories()->delete();
        $categories = array_map('trim', explode(',', $request->categories));
        foreach ($categories as $cat) {
            $vendor->categories()->create(['name' => $cat]);
        }

        // 3. Update Contact (Update baris pertama)
        $vendor->contacts()->first()->update([
            'name' => $request->pic_name,
            'phone' => $request->pic_phone,
        ]);

        return redirect()->route('owner.vendors')->with('success', 'Vendor successfully updated!');
    }
}
