<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10); // Chỉ hiển thị bản ghi chưa bị ẩn
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'deleted' => false,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật');
    }

    public function show($id)
{
    $category = Category::findOrFail($id);
    $books = $category->books()->with('details', 'images')->paginate(12);

    return view('admin.categories.show', compact('category', 'books'));
}

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['deleted' => true]); // Xóa mềm

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã bị xóa');
    }

        // 👉 Hiển thị danh mục phía client

}
