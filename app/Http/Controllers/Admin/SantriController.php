<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\KenaikanKelasHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SantriController extends Controller
{
    public function create()
    {
        $kategori = KategoriSantri::all();
        $jenjang = ['SMP', 'SMA'];
        $kelas = [
            'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
            'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
        ];
        $wali = User::where('role', 'wali')->get();

        return view('admin.santri.create', compact('kategori', 'jenjang', 'kelas', 'wali'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|unique:santri',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => 'required',
            'kategori_id' => 'required|exists:kategori_santri,id',
            'status' => 'required|in:aktif,non-aktif'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $santri = Santri::create($request->all());
            
            DB::commit();
            
            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Data santri berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(Santri $santri)
    {
        // Debug route information
        \Illuminate\Support\Facades\Log::info('Edit method accessed', [
            'santri_id' => $santri->id,
            'url' => request()->url(),
            'method' => request()->method(),
            'route_parameters' => request()->route()->parameters(),
            'middleware' => request()->route()->middleware(),
            'action' => request()->route()->getActionName()
        ]);
        $kategori_santri = KategoriSantri::all();
        $jenjang = ['SMP', 'SMA'];
        $kelas = [
            'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
            'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
        ];
        
        // Hilangkan data yang tidak diperlukan di form edit
        return view('admin.santri.edit', compact('santri', 'kategori_santri', 'jenjang', 'kelas'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|unique:santri,nisn,' . $santri->id,
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => 'required',
            'kategori_id' => 'required|exists:kategori_santri,id',
            'status' => 'required|in:aktif,lulus,keluar'
        ]);

        if ($validator->fails()) {
            Log::error('Validasi update santri gagal', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $santri->update($request->all());
            
            DB::commit();
            
            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Data santri berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error update santri: ' . $e->getMessage(), [
                'santri_id' => $santri->id,
                'input' => $request->all()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Santri $santri)
    {
        try {
            DB::beginTransaction();
            
            $santri->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data santri berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Santri $santri)
    {
        $pembayaranPerTahun = [];
        $tahunSekarang = date('Y');
        
        // Ambil pembayaran untuk 2 tahun terakhir
        for ($tahun = $tahunSekarang; $tahun >= $tahunSekarang - 1; $tahun--) {
            $pembayaranList = [];
            
            // Generate data untuk 12 bulan
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $pembayaran = $santri->pembayaran()
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->first();
                
                if ($pembayaran) {
                    $pembayaranList[] = $pembayaran;
                } else {
                    // Jika tidak ada pembayaran, buat object kosong
                    $pembayaranList[] = (object)[
                        'bulan' => sprintf('%02d', $bulan),
                        'nominal' => 0,
                        'status' => 'belum_bayar'
                    ];
                }
            }
            
            $pembayaranPerTahun[$tahun] = $pembayaranList;
        }

        // Hitung total tunggakan
        $totalTunggakan = $santri->pembayaran()
            ->where('status', '!=', 'success')
            ->sum('nominal');

        return view('admin.santri.show', compact('santri', 'pembayaranPerTahun', 'totalTunggakan'));
    }

    public function index()
    {
        // Muat data santri hanya di halaman index
        $santri = Santri::with(['kategori', 'wali'])
            ->orderBy('nama')
            ->get();
            
        return view('admin.santri.index', compact('santri'));
    }

    public function riwayat()
    {
        $riwayat = KenaikanKelasHistory::with(['santri', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.santri.riwayat', compact('riwayat'));
    }

    public function kenaikanKelas()
    {
        DB::beginTransaction();
        try {
            // Ambil semua santri aktif
            $santriAktif = Santri::where('status', 'aktif')->get();
            $currentYear = date('Y');
            $berhasil = 0;
            $errors = [];

            foreach ($santriAktif as $santri) {
                try {
                    $kelasAwal = $santri->kelas;
                    $match = preg_match('/^(\d+)(.*)$/', $kelasAwal, $matches);

                    if (!$match) {
                        throw new \Exception("Format kelas tidak valid");
                    }

                    $tingkat = (int)$matches[1];
                    $suffix = $matches[2] ?? '';

                    // Handle kasus khusus
                    if (($santri->jenjang === 'SMP' && $tingkat === 9) ||
                        ($santri->jenjang === 'SMA' && $tingkat === 12)) {
                        // Santri lulus
                        $santri->status = 'lulus';
                        $santri->tahun_tamat = $currentYear;
                        $santri->save();

                        KenaikanKelasHistory::create([
                            'santri_id' => $santri->id,
                            'kelas_sebelum' => $kelasAwal,
                            'kelas_sesudah' => null,
                            'status' => 'lulus',
                            'created_by' => Auth::id()
                        ]);

                        $berhasil++;
                        continue;
                    }

                    // Kenaikan kelas normal
                    $kelasBaru = ($tingkat + 1) . $suffix;
                    $santri->update(['kelas' => $kelasBaru]);

                    KenaikanKelasHistory::create([
                        'santri_id' => $santri->id,
                        'kelas_sebelum' => $kelasAwal,
                        'kelas_sesudah' => $kelasBaru,
                        'status' => 'aktif',
                        'created_by' => Auth::id()
                    ]);

                    $berhasil++;

                } catch (\Exception $e) {
                    $errors[] = "Gagal memproses santri {$santri->nama}: " . $e->getMessage();
                }
            }

            if (empty($errors)) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => "Berhasil memproses kenaikan kelas untuk {$berhasil} santri"
                ]);
            }

            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => implode("\n", $errors)
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batalKenaikanKelas(Request $request)
    {
        DB::beginTransaction();
        try {
            $santriIds = $request->santri_ids;
            $histories = KenaikanKelasHistory::whereIn('santri_id', $santriIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('santri_id');

            foreach ($histories as $santriId => $riwayat) {
                if ($riwayat->isEmpty()) continue;

                $lastHistory = $riwayat->first();
                $santri = Santri::find($santriId);

                if (!$santri) continue;

                // Kembalikan ke kelas sebelumnya
                $santri->update([
                    'kelas' => $lastHistory->kelas_sebelum,
                    'status' => 'aktif',
                    'tahun_tamat' => null
                ]);

                // Hapus riwayat kenaikan kelas terakhir
                $lastHistory->delete();
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil membatalkan kenaikan kelas'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function __construct()
    {
        \Illuminate\Support\Facades\Log::info('Route information for each request', [
            'url' => request()->url(),
            'method' => request()->method(),
            'route' => request()->route() ? [
                'name' => request()->route()->getName(),
                'parameters' => request()->route()->parameters(),
                'action' => request()->route()->getActionName()
            ] : null
        ]);
    }

    public function kelas($jenjang, $kelas)
    {
        // Log untuk debugging
        Log::info('Kelas method accessed', [
            'jenjang' => $jenjang,
            'kelas' => $kelas,
            'url' => request()->url(),
            'method' => request()->method(),
            'route' => request()->route() ? [
                'name' => request()->route()->getName(),
                'parameters' => request()->route()->parameters()
            ] : null
        ]);

        // Validasi format jenjang dan kelas
        if (!in_array(strtoupper($jenjang), ['SMP', 'SMA'])) {
            abort(404);
        }

        $validKelas = $jenjang == 'smp' 
            ? ['7A', '7B', '8A', '8B', '9A', '9B']
            : ['10A', '10B', '11A', '11B', '12A', '12B'];

        if (!in_array(strtoupper($kelas), $validKelas)) {
            abort(404);
        }

        $santri = Santri::with(['kategori', 'wali'])
            ->where('jenjang', strtoupper($jenjang))
            ->where('kelas', strtoupper($kelas))
            ->where('status', 'aktif')
            ->get();

        $currentKelas = [
            'jenjang' => strtoupper($jenjang),
            'kelas' => strtoupper($kelas)
        ];

        return view('admin.santri.index', compact('santri', 'currentKelas'));
    }
}
