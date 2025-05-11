<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\PinjamanAngunan;
use App\Models\PinjamanEmergency;
use App\Models\PinjamanNonAngunan;
use App\Models\PinjamanRegular;
use App\Models\SimpananBerjangka;
use App\Models\SimpananPokok;
use App\Models\SimpananSukarela;
use App\Models\SimpananWajib;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AnggotaController extends Controller
{
    public function index()
    {
        // Mengambil semua data anggota dari database dan mengurutkan berdasarkan kolom created_at secara descending
        $anggota = DB::table('anggota')->orderBy('created_at', 'desc')->get();
        dd($anggota);


        // Mengirimkan data anggota ke view
        return view('pages.manager.approve_regis_manager', [
            'title' => 'Data Approve Registrasi',
            'anggota' => $anggota,
        ]);
    }
    public function index2()
    {
        return view('pages.ketua.approve_regis_ketua', [
            'title' => 'Data Approve Registrasi',
            'anggota' => Anggota::all()

        ]);
    }
    public function laporanregisadmin()
    {
        return view('pages.admin.laporan_regis_admin', [
            'title' => 'Data Approve Registrasi',
            'anggota' => Anggota::all()

        ]);
    }


    // Fungsi untuk menghitung dan menambahkan jumlah ke PinjamanRegular
    public function addAmount()
    {
        $userId = auth()->id();

        // Hitung total dari PinjamanAngunan dan PinjamanNonAngunan untuk user yang sedang login
        $totalAngunan = PinjamanAngunan::where('user_id', $userId)->sum('amount');
        $totalNonAngunan = PinjamanNonAngunan::where('user_id', $userId)->sum('amount');

        // Hitung total keseluruhan
        $total = $totalAngunan + $totalNonAngunan;

        // Simpan atau update data di PinjamanRegular
        $regular = PinjamanRegular::updateOrCreate(
            ['user_id' => $userId],
            ['amount' => $total]
        );

        return $regular->amount;
    }

    /**
     * Menampilkan dashboard anggota dengan data yang relevan.
     *
     * @return \Illuminate\View\View
     */
    public function homeanggota()
    {
        $userId = auth()->id();

        // Hitung dan update data PinjamanRegular
        $totalRegular = $this->addAmount();

        // Hitung total data lainnya
        // $totalPending = SimpananBerjangka::where('user_id', $userId)->where('status_payment', 'success')->sum('nominal');
        $saldoEmergency = PinjamanEmergency::where('user_id', $userId)->where('status', 'success')->sum('amount');
        $totalSimpanan = SimpananBerjangka::where('user_id', $userId)->where('status_payment', 'success')->sum('nominal') + SimpananSukarela::where('user_id', $userId)->where('status_payment', 'success')->sum('nominal');
        $anggota_id = Anggota::where('email_kantor', Auth::user()->email)->value('id');
        $simpananPokok = SimpananPokok::where('anggota_id', $anggota_id)->value('nominal');
        $simpananId = SimpananPokok::where('anggota_id', $anggota_id)->value('id');
        // dd($simpananId);


        // Kirim data ke view
        return view('pages.anggota.home_anggota', [
            'title' => 'Dashboard Anggota',
            'totalSimpanan' => $totalSimpanan,
            // 'totalPending' => $totalPending,
            'saldoEmergency' => $saldoEmergency,
            'saldoRegular' => $totalRegular,
            'simpanan' => $simpananPokok,
            'anggota_id' => $anggota_id,
            'simpanan_id' => $simpananId,
        ]);
    }


    public function tambahsimpanan()
    {
        return view('pages.anggota.simpanan.tambah_simpanan', [
            'title' => 'Tambah Simpanan',
        ]);
    }

    public function tranfersimpanan()
    {
        return view('pages.anggota.transfer_simpanan', [
            'title' => 'Simpanan',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input yang diperlukan
        $request->validate(
            [
                'nama' => 'required|string|max:255',
                'alamat_domisili' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tgl_lahir' => 'required|date',
                'alamat_ktp' => 'required|string|max:255',
                'nik' => 'required|string|max:255',
                'email_kantor' => 'required|email|string|max:255|unique:anggota',
                'no_handphone' => 'required|string|max:255|regex:/^8[0-9]{0,254}$/',
                'simpanan_pokok' => 'required|numeric|min:1000000',
                'simpanan_wajib' => 'required|numeric|min:50000',
                'metode_pembayaran' => 'required|string|max:255',
            ],
            [
                'nama.required' => 'Nama wajib diisi',
                'alamat_domisili.required' => 'Alamat domisili wajib diisi',
                'tempat_lahir.required' => 'Tempat Lahir wajib diisi',
                'tgl_lahir.required' => 'Tanggal Lahir wajib diisi',
                'tgl_lahir.date' => 'Tanggal Lahir harus berupa tanggal yang valid',
                'alamat_ktp.required' => 'Alamat KTP wajib diisi',
                'nik.required' => 'NIK wajib diisi',
                'email_kantor.required' => 'Email wajib diisi',
                'email_kantor.email' => 'Silahkan masukkan email yang valid',
                'email_kantor.unique' => 'Email sudah ada, silahkan pilih email lain',
                'no_handphone.required' => 'No handphone wajib diisi dan format 82258756166',
            ]
        );

        // Simpan anggota baru
        $anggota = Anggota::create([
            'nama' => $request->nama,
            'alamat_domisili' => $request->alamat_domisili,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat_ktp' => $request->alamat_ktp,
            'nik' => $request->nik,
            'email_kantor' => $request->email_kantor,
            'no_handphone' => '0' . ltrim($request->no_handphone, '0'),
            'password' => Hash::make($request->password),
        ]);

        // Simpan simpanan wajib
        $simpananWajib = SimpananWajib::create([
            'no_simpanan_wajib' => $this->generateNomorSimpananWajib(),
            'nominal' => $request->simpanan_wajib,
            'metode_pembayaran' => 'Potongan Gaji Otomatis',
            'status_pembayaran' => 'pending',
            'anggota_id' => $anggota->id,
        ]);

        // Simpan simpanan pokok
        $simpananPokok = SimpananPokok::create([
            'no_simpanan_pokok' => $this->generateNomorSimpanan(),
            'nominal' => $request->simpanan_pokok,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'pending',
            'anggota_id' => $anggota->id,
        ]);

        // Log
        Log::info('Anggota dan Simpanan berhasil ditambahkan:', [
            'anggota' => $anggota->toArray(),
            'simpanan_wajib' => $simpananWajib->toArray(),
            'simpanan_pokok' => $simpananPokok->toArray(),
        ]);

        return redirect()->back()->with('success', 'Data Berhasil ditambahkan!');
    }
    private function generateNomorSimpanan()
    {
        // Format nomor simpanan: SS-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "SP-{$date}-{$random}";
    }
    // Fungsi untuk menghasilkan nomor simpanan unik
    private function generateNomorSimpananWajib()
    {
        // Format nomor simpanan: SS-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "SW-{$date}-{$random}";
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return response()->json($anggota); // Mengembalikan data anggota untuk diisi dalam form
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'nik' => 'required|string|max:20',
            'email_kantor' => 'required|email|max:255',
            'no_handphone' => 'required|string|max:15',
            'alamat_domisili' => 'required|string|max:255',
            'alamat_ktp' => 'required|string|max:255',
        ]);

        $anggota = Anggota::findOrFail($id);

        // Bandingkan apakah data yang dikirim berbeda dari data lama
        $dataBaru = $request->only([
            'nama',
            'tempat_lahir',
            'tgl_lahir',
            'nik',
            'email_kantor',
            'no_handphone',
            'alamat_domisili',
            'alamat_ktp'
        ]);

        $dataLama = $anggota->only(array_keys($dataBaru));

        if ($dataBaru == $dataLama) {
            return redirect()->back()->with('info', 'Tidak ada perubahan data.');
        }

        // Jika ada perubahan, update data
        $anggota->update($dataBaru);

        return redirect()->back()->with('success', 'Data berhasil diedit!');
    }

    public function delete($id)
    {
        // dd()
        $anggota = User::findOrFail($id);
        $anggota->active = 0;
        $anggota->save();
        // $anggota->delete();

        return redirect()->back()->with('success', 'Data anggota berhasil dinonaktifkan!');
    }
    public function active($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->active = 1;
        $anggota->save();
        // $anggota->delete();

        return redirect()->back()->with('success', 'Data anggota berhasil diaktifkan!');
    }
}
