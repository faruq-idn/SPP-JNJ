<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\User;
use App\Models\PembayaranSpp;
use App\Models\MetodePembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::with(['kategori', 'wali'])
            ->orderBy('nama')
            ->get();
            
        return view('admin.santri.index', compact('santri'));
    }

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
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $santri = Santri::create($request->all());
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data santri berhasil ditambahkan'
            ]);
                
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Santri $santri)
    {
        $santri->load('wali');
        return response()->json($santri);
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
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $santri->update($request->all());
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data santri berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error update santri: ' . $e->getMessage(), [
                'santri_id' => $santri->id,
                'input' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
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
        $santri->load(['kategori.tarifTerbaru', 'wali']);
        
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');

        $pembayaranPerTahun = [];
        $totalTunggakanPerTahun = [];
        
        // Ambil pembayaran untuk 2 tahun terakhir
        for ($tahun = $tahunSekarang; $tahun >= $tahunSekarang - 1; $tahun--) {
            $pembayaranList = [];
            $tunggakanTahun = 0;
            
            // Generate data hanya untuk bulan yang sudah lewat atau bulan sekarang
            $bulanMaksimal = ($tahun == $tahunSekarang) ? $bulanSekarang : 12;
            
            for ($bulan = 1; $bulan <= $bulanMaksimal; $bulan++) {
                $pembayaran = $santri->pembayaran()
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->with('metode_pembayaran')
                    ->first();

                $nominal = $santri->kategori->tarifTerbaru->nominal ?? 0;

                if ($pembayaran) {
                    if ($pembayaran->status !== 'success') {
                        $tunggakanTahun += $pembayaran->nominal;
                    }
                    $pembayaranList[] = $pembayaran;
                } else {
                    // Tambahkan ke tunggakan
                    $tunggakanTahun += $nominal;
                    
                    // Buat object untuk bulan ini
                    $pembayaranList[] = (object)[
                        'bulan' => $bulan,
                        'nama_bulan' => Carbon::create()->month($bulan)->translatedFormat('F'),
                        'nominal' => $nominal,
                        'status' => 'unpaid',
                        'tahun' => $tahun,
                        'metode_pembayaran' => null,
                        'tanggal_bayar' => null
                    ];
                }
            }
            
            if (!empty($pembayaranList)) {
                $pembayaranPerTahun[$tahun] = collect($pembayaranList)->sortBy('bulan');
                $totalTunggakanPerTahun[$tahun] = $tunggakanTahun;
            }
        }

        // Hitung total tunggakan keseluruhan
        $totalTunggakan = array_sum($totalTunggakanPerTahun);

        // Hitung status SPP
        $statusSpp = $this->hitungStatusSpp($santri, $tahunSekarang);

        $metode = MetodePembayaran::all();

        return view('admin.santri.show', compact(
            'santri',
            'pembayaranPerTahun',
            'totalTunggakan',
            'totalTunggakanPerTahun',
            'statusSpp',
            'metode'
        ));
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        $pembayaran = PembayaranSpp::findOrFail($id);

        try {
            DB::beginTransaction();
            $pembayaran->update([
                'status' => 'success',
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function hitungStatusSpp($santri, $tahun)
    {
        $pembayaranTahunIni = $santri->pembayaran()
            ->where('tahun', $tahun)
            ->whereMonth('created_at', '<=', now()->month)
            ->get();

        if ($pembayaranTahunIni->isEmpty()) {
            return 'Belum Lunas';
        }

        return $pembayaranTahunIni->every(function ($pembayaran) {
            return $pembayaran->status === 'success';
        }) ? 'Lunas' : 'Belum Lunas';
    }

    public function kelas($jenjang, $kelas)
    {
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
