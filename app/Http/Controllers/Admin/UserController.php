<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('id', '<>', Auth::id());

        if ($request->query('type')) {
            $query->where('role', $request->query('type'));
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin', 'petugas', 'wali'];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,petugas,wali'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'petugas', 'wali'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:admin,petugas,wali'
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($user->santri()->exists() || $user->pembayaran()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pengguna yang memiliki data terkait');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus');
    }
}
