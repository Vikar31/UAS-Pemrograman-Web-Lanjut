<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangCount         = Barang::count();
        $barangMasukCount    = BarangMasuk::count();
        $barangKeluarCount   = BarangKeluar::count();
        $userCount           = User::count();
    
        // Barang Masuk Per Bulan
        $barangMasukPerBulan = BarangMasuk::selectRaw('DATE_FORMAT(tanggal_masuk, "%Y-%m") as date, SUM(jumlah_masuk) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($data) {
                $data->date = date('Y-m', strtotime($data->date));
                $data->total = (int) $data->total;
                return $data;
            });
    
        // Barang Keluar Per Bulan
        $barangKeluarPerBulan = BarangKeluar::selectRaw('DATE_FORMAT(tanggal_keluar, "%Y-%m") as date, SUM(jumlah_keluar) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($data) {
                $data->date = date('Y-m', strtotime($data->date));
                $data->total = (int) $data->total;
                return $data;
            });

        // Buat label bulan dari data barang masuk
        $labels = $barangMasukPerBulan->pluck('date')->map(function($d) {
            return date('F', strtotime($d)); // Hasil: "January", "February", dll.
        });
    
        // Ambil barang dengan stok rendah (termasuk stok negatif)
        $barangMinimum = Barang::where('stok', '<=', 500)
            ->orderBy('stok', 'asc')
            ->get();
    
        return view('dashboard', [
            'barang'            => $barangCount,
            'barangMasuk'       => $barangMasukCount,
            'barangKeluar'      => $barangKeluarCount,
            'user'              => $userCount,
            'barangMasukData'   => $barangMasukPerBulan,
            'barangKeluarData'  => $barangKeluarPerBulan,
            'barangMinimum'     => $barangMinimum,
            'labels'            => $labels // ← ini dikirim ke Blade
        ]);
    }

    // Fungsi lainnya kosong sesuai kode kamu
}
