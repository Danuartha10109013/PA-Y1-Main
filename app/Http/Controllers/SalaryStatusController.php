<?php

namespace App\Http\Controllers;

use App\Imports\SalaryStatusImport;
use App\Models\Anggota;
use App\Models\PengajuanPinjaman;
use App\Models\SalaryStatus;
use App\Models\Simpanan;
use App\Models\SimpananWajib;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalaryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pinjamanAktif = PengajuanPinjaman::where('status_ketua','Diterima')->orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin.gaji.index', [
            'title' => 'Data Potongan Gaji',
            'pinjamanAktif' => $pinjamanAktif,
        ]);
    }
    public function index_sw()
    {
        $anggota = Anggota::all();
        $month = now()->format('m');
        $year = now()->format('Y');
        foreach ($anggota as $a) {

            $thisMonth = SimpananWajib::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('anggota_id', $a->id)
                ->count();

            // dd($thisMonth);

            if ($thisMonth == 0) {
                $simpananWajib = SimpananWajib::create([
                    'no_simpanan_wajib' => $this->generateNomorSimpananWajib(),
                    'nominal' => 50000.00,
                    'metode_pembayaran' => 'Potongan Gaji Otomatis',
                    'status_pembayaran' => 'pending', // Set default status pembayaran
                    'anggota_id' => $a->id, // Relasi ke anggota yang baru dibuat
                ]);
            }
        }

        return view('pages.admin.gaji.potongan_wajib', [
            'title' => 'Data Potongan Gaji Simpanan Wajib',
            'anggota' => $anggota,
        ]);
    }

    private function generateNomorSimpananWajib()
    {
        // Format nomor simpanan: SS-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "SW-{$date}-{$random}";
    }

    public function updatePembayaran($id)
    {
        $data = SimpananWajib::find($id);
        $data->status_pembayaran = 'sukses';
        $data->tanggal_pembayaran = now()->format('Y-m-d');
        $data->save();
        return redirect()->route('data.potongan.gaji.sw')->with('succes', 'Pembayaran telah dikonfirmasi');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($uuid,$month)
    {
        $pinjaman = PengajuanPinjaman::where('uuid', $uuid)->firstOrFail();

        return view('pages.admin.gaji.import', [
            'title' => 'Import Data Potongan Gaji',
            'pinjamanAktif' => $pinjaman,
            'month' => $month,
        ]);
    }
    public function create_sw($id)
    {
        $pinjaman = SimpananWajib::find($id);
        // dd($pinjaman);
        return view('pages.admin.gaji.import_sw', [
            'title' => 'Import Data Potongan Gaji',
            'pinjamanAktif' => $pinjaman,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $uuid, $month)
    {
        // dd($request->all());
        $pengajuanPinjaman = PengajuanPinjaman::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti Pembayaran wajib diisi.',
            'bukti_pembayaran.image' => 'File yang diunggah harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar yang diperbolehkan: jpeg, jpg, png.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.',
        ]);


        $currentMonth = now()->format('Y-m');
        $existingPayment = SalaryStatus::where('pengajuan_pinjamans_id', $pengajuanPinjaman->id)
            ->where('created_at', 'like', "$currentMonth%")
            ->first();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'Pembayaran untuk bulan ini sudah dilakukan.');
        }

        $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran');

        $pengajuanPinjaman->sisa_pinjaman -= $pengajuanPinjaman->nominal_angsuran;
        $pengajuanPinjaman->sisa_jangka_waktu -= 1;

        $pengajuanPinjaman->status_pembayaran = $pengajuanPinjaman->sisa_pinjaman <= 0
            ? 'Lunas'
            : 'Aktif';

        if ($pengajuanPinjaman->sisa_pinjaman <= 0) {
            $pengajuanPinjaman->sisa_pinjaman = 0;
        }

        $tenor = json_decode($pengajuanPinjaman->tenor, true); // Pastikan hasilnya array, bukan object
        // dd($tenor);
        // Bulan yang ingin diupdate
        $monthToUpdate = $month; // Contoh input dari $month

        // Loop dan update status
        foreach ($tenor as &$t) {
            if ($t['bulan'] === $monthToUpdate) {
                $t['status'] = 1;
            }
        }

        // Simpan kembali ke database
        $pengajuanPinjaman->tenor = json_encode($tenor);

        $pengajuanPinjaman->save();

        SalaryStatus::create([
            'pengajuan_pinjamans_id' => $pengajuanPinjaman->id,
            'jumlah_pembayaran' => $pengajuanPinjaman->nominal_angsuran,
            'bukti_pembayaran' => "storage/$buktiPath",
            'status' => 'sukses',
        ]);

        return redirect()->route('data.potongan.gaji')->with('success', 'Bukti pembayaran berhasil diunggah dan data telah diperbarui.');
    }
    public function store_sw(Request $request, $id)
    {
        // Ambil data simpanan wajib berdasarkan ID
        $data = SimpananWajib::findOrFail($id);

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti Pembayaran wajib diisi.',
            'bukti_pembayaran.image' => 'File yang diunggah harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar yang diperbolehkan: jpeg, jpg, png.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');

            // Simpan file ke folder public/storage/bukti_pembayaran
            $path = $file->store('bukti_simpanan_wajib', 'public');

            // Simpan path ke database
            $data->image = $path;
        }

        // Update status dan tanggal pembayaran
        $data->status_pembayaran = 'sukses';
        $data->tanggal_pembayaran = now()->format('Y-m-d');
        $data->save();

        // Redirect ke route dengan notifikasi sukses
        return redirect()->route('data.potongan.gaji.sw')
            ->with('success', 'Pembayaran telah dikonfirmasi');
    }


    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        // Ambil data pengajuan pinjaman berdasarkan UUID
        $pengajuanPinjaman = PengajuanPinjaman::where('uuid', $uuid)->firstOrFail();

        // Ambil riwayat pembayaran terkait pinjaman tersebut
        $salaryHistory = SalaryStatus::where('pengajuan_pinjamans_id', $pengajuanPinjaman->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('pages.admin.gaji.show', [
            'title' => 'Riwayat Potongan Gaji Anggota',
            'pengajuanPinjaman' => $pengajuanPinjaman,
            'salaryHistory' => $salaryHistory,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryStatus $salaryStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryStatus $salaryStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryStatus $salaryStatus)
    {
        //
    }
}
