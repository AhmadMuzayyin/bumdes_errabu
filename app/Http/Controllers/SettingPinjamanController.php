<?php

namespace App\Http\Controllers;

use App\Models\SettingPinjaman;
use Illuminate\Http\Request;

class SettingPinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan atau membuat setting pinjaman
        $setting = SettingPinjaman::first();
        if (!$setting) {
            $setting = SettingPinjaman::create([
                'bunga' => 5 // default bunga 5%
            ]);
        }

        return view('simpan-pinjam.setting_pinjaman.index', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SettingPinjaman $settingPinjaman)
    {
        $request->validate([
            'bunga' => 'required|numeric|min:0|max:100',
        ], [
            'bunga.required' => 'Persentase bunga harus diisi',
            'bunga.numeric' => 'Persentase bunga harus berupa angka',
            'bunga.min' => 'Persentase bunga minimal 0',
            'bunga.max' => 'Persentase bunga maksimal 100',
        ]);

        $settingPinjaman->update([
            'bunga' => $request->bunga
        ]);

        return redirect()->route('setting-pinjaman.index')
            ->with('success', 'Pengaturan bunga pinjaman berhasil diperbarui');
    }
}
