<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng với tìm kiếm
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('keyword') && $request->keyword !== null) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'role' => 'required|in:user,admin',
            'is_active' => 'nullable|boolean',
            'birth_date' => 'nullable|date',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'] ?? null,
            'avatar_url' => $avatarPath,
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? true,
            'birth_date' => $validated['birth_date'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được thêm thành công');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'role' => 'required|in:user,admin',
            'is_active' => 'nullable|boolean',
            'birth_date' => 'nullable|date',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            $avatarPath = $user->avatar_url;
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'avatar_url' => $avatarPath,
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? $user->is_active,
            'birth_date' => $validated['birth_date'] ?? $user->birth_date,
        ]);

        if ($request->filled('password')) {
            $validatedPassword = $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);
            $user->update([
                'password' => Hash::make($validatedPassword['password']),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được cập nhật');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được xóa');
    }
}
