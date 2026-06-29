<?php

namespace App\Http\Controllers;

use App\Models\EoPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EoPortfolioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $portfolios = EoPortfolio::when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());

        return view('owner.portfolios', compact('portfolios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('eo_portfolios', 'public');

        EoPortfolio::create([
            'title' => $request->title,
            'description' => $request->description ?? '-',
            'image_path' => $imagePath,
        ]);

        return redirect()->route('owner.portfolios.index')->with('success', 'Portofolio EO berhasil ditambahkan!');
    }

    public function update(Request $request, EoPortfolio $portfolio)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description ?? '-',
        ];

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($portfolio->image_path)) {
                Storage::disk('public')->delete($portfolio->image_path);
            }
            $data['image_path'] = $request->file('image')->store('eo_portfolios', 'public');
        }

        $portfolio->update($data);

        return redirect()->route('owner.portfolios.index')->with('success', 'Portofolio EO berhasil diperbarui!');
    }

    public function destroy(EoPortfolio $portfolio)
    {
        if (Storage::disk('public')->exists($portfolio->image_path)) {
            Storage::disk('public')->delete($portfolio->image_path);
        }

        $portfolio->delete();

        return redirect()->route('owner.portfolios.index')->with('success', 'Portofolio EO berhasil dihapus!');
    }
}
