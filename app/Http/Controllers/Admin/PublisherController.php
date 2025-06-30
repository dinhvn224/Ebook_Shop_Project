<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::withoutGlobalScopes();

        if ($request->has('keyword') && $request->keyword !== null) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $publishers = $query->paginate(10)->withQueryString();

        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Publisher::create([
            'name' => $request->name,
            'deleted' => false,
        ]);

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã được tạo thành công');
    }

    public function edit($id)
    {
        $publisher = Publisher::withoutGlobalScopes()->findOrFail($id);
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $publisher = Publisher::withoutGlobalScopes()->findOrFail($id);
        $publisher->update(['name' => $request->name]);

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã được cập nhật');
    }

    public function destroy($id)
    {
        $publisher = Publisher::withoutGlobalScopes()->findOrFail($id);
        $publisher->update(['deleted' => true]);

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã bị xóa');
    }

    public function restore($id)
    {
        $publisher = Publisher::withoutGlobalScopes()->findOrFail($id);
        $publisher->update(['deleted' => false]);

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã được khôi phục');
    }
}
