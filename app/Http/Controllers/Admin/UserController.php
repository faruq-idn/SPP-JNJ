<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');

        $users = User::with('santri')
            ->when($type, function ($query, $type) {
                return $query->where('role', $type);
            })
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function getData(User $user)
    {
        $user->load('santri');
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function getSantri(User $user)
    {
        return response()->json([
            'success' => true,
            'santri' => $user->santri
        ]);
    }

    public function searchSantri(Request $request)
    {
        $search = $request->get('q');
        $waliId = $request->get('wali_id');

        $query = Santri::aktif()  // Hanya tampilkan santri dengan status aktif
            ->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            })
            ->with('wali:id,name');  // Load wali untuk semua santri

        \Illuminate\Support\Facades\Log::info('Search Santri Query', [
            'search' => $search,
            'wali_id' => $waliId,
            'query' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $santri = $query->limit(10)->get();

        \Illuminate\Support\Facades\Log::info('Search Santri Results', [
            'count' => $santri->count(),
            'data' => $santri->toArray()
        ]);

        $results = $santri->map(function($item) use ($waliId) {
            $text = $item->nama . ' (' . $item->nisn . ')';
            if ($item->wali && $item->wali_id != $waliId) {
                $text .= ' - Sudah terhubung dengan wali: ' . $item->wali->name;
            }
            return [
                'id' => $item->id,
                'text' => $text,
                'disabled' => $item->wali_id && $item->wali_id != $waliId
            ];
        });
        
        $response = ['results' => $results];

        \Illuminate\Support\Facades\Log::info('Search Santri Response', $response);

        return response()->json($response)
            ->header('Content-Type', 'application/json')
            ->header('X-Requested-With', 'XMLHttpRequest');
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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,petugas,wali',
                'no_hp' => 'required|string|max:15',
                'santri_ids' => 'nullable|array', // Santri IDs menjadi nullable
                'santri_ids.*' => 'exists:santri,id'
            ]);

            DB::beginTransaction();
            try {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                    'role' => $validated['role'],
                    'no_hp' => $validated['no_hp']
                ]);

                // Jika role wali dan ada santri dipilih
                if ($validated['role'] === 'wali' && !empty($request->santri_ids)) {
                    Santri::whereIn('id', $request->santri_ids)->update(['wali_id' => $user->id]);
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna berhasil ditambahkan',
                    'data' => $user
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|in:admin,petugas,wali',
                'no_hp' => 'required|string|max:15',
                'password' => 'nullable|string|min:8|confirmed',
                'santri_ids' => 'nullable|array', // Santri IDs menjadi nullable
                'santri_ids.*' => 'exists:santri,id'
            ]);

            DB::beginTransaction();
            try {
                $data = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                    'no_hp' => $validated['no_hp']
                ];

                if ($request->filled('password')) {
                    $data['password'] = bcrypt($validated['password']);
                }

                $user->update($data);

                // Update relasi santri
                if ($validated['role'] === 'wali') {
                    // Reset wali_id untuk santri yang sebelumnya terhubung dengan user ini
                    Santri::where('wali_id', $user->id)->update(['wali_id' => null]);
                    
                    // Update wali_id untuk santri yang dipilih
                    if (!empty($request->santri_ids)) {
                        Santri::whereIn('id', $request->santri_ids)->update(['wali_id' => $user->id]);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna berhasil diperbarui',
                    'data' => $user
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}
