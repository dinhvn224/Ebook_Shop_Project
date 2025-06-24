<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị danh sách các danh mục
    public function index()
    {
        $categories = Category::paginate(10);  // Phân trang 10 danh mục mỗi trang
        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form tạo mới danh mục
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tạo danh mục mới
        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công');
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Cập nhật thông tin danh mục
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công');
    }

    // Xóa danh mục (thực sự xóa khỏi cơ sở dữ liệu)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã bị xóa hoàn toàn');
    }
}
