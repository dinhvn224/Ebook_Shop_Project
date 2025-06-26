<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::paginate(10);
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Author::create([
            'name' => $request->name,
            'deleted' => false,
        ]);

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã được tạo');
    }

    public function edit($id)
    {
        $author = Author::withoutGlobalScopes()->findOrFail($id);
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $author = Author::withoutGlobalScopes()->findOrFail($id);
        $author->update(['name' => $request->name]);

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã được cập nhật');
    }

    public function destroy($id)
    {
        $author = Author::withoutGlobalScopes()->findOrFail($id);
        $author->update(['deleted' => true]);

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã bị xóa');
    }

    public function restore($id)
    {
        $author = Author::withoutGlobalScopes()->findOrFail($id);
        $author->update(['deleted' => false]);

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã được khôi phục');
    }
}
