<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    // Hiển thị danh sách nhà sản xuất
    public function index()
    {
        // Lấy tất cả nhà sản xuất và phân trang 10 nhà sản xuất mỗi trang
        $publishers = Publisher::paginate(10);
        return view('admin.publishers.index', compact('publishers'));
    }

    // Hiển thị form tạo mới nhà sản xuất
    public function create()
    {
        return view('admin.publishers.create');
    }

    // Lưu nhà sản xuất mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tạo nhà sản xuất mới
        Publisher::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã được tạo thành công');
    }

    // Hiển thị form chỉnh sửa nhà sản xuất
    public function edit($id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('admin.publishers.edit', compact('publisher'));
    }

    // Cập nhật thông tin nhà sản xuất
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $publisher = Publisher::findOrFail($id);
        $publisher->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã được cập nhật thành công');
    }

    // Xóa nhà sản xuất (thực sự xóa khỏi cơ sở dữ liệu)
    public function destroy($id)
    {
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();

        return redirect()->route('admin.publishers.index')->with('success', 'Nhà sản xuất đã được xóa ');
    }
}
