<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan tipe user (petugas/wali)
        if ($request->has('type')) {
            $query->where('role', $request->type);
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $type = $request->get('type', 'wali');

        $users = User::where('role', $type)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'no_hp'])
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                    'name' => $user->name,
                    'email' => $user->email,
                    'no_hp' => $user->no_hp
                ];
            });

        return response()->json($users);
    }

    public function create()
    {
        $roles = [
            'admin' => 'Admin',
            'petugas' => 'Petugas',
            'wali' => 'Wali Santri'
        ];
        
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,petugas,wali',
            'no_hp' => 'required|string|max:15'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = [
            'admin' => 'Admin',
            'petugas' => 'Petugas',
            'wali' => 'Wali Santri'
        ];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,wali',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
