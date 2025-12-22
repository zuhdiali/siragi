<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\POK;

class POKController extends Controller
{
    public function filterPOK(Request $request)
    {
        $pok_awal = POK::where('id', $request->kak6_program)->first();
        $query = POK::where('kode_program', $pok_awal->kode_program);

        if ($request->kak6_aktivitas) {
            $pok_awal = POK::where('id', $request->kak6_aktivitas)->first();
            $query->where('kode_aktivitas', $pok_awal->kode_aktivitas);
        } else {
            return response()->json($query->whereNull('kode_klasifikasi_rincian_output')->whereNotNull('kode_aktivitas')
                ->orderBy('kode_aktivitas', 'asc')->get());
        }

        if ($request->kak6_kro) {
            $pok_awal = POK::where('id', $request->kak6_kro)->first();
            $query->where('kode_klasifikasi_rincian_output', $pok_awal->kode_klasifikasi_rincian_output);
        } else {
            return response()->json($query->whereNull('kode_rincian_output')->whereNotNull('kode_klasifikasi_rincian_output')
                ->orderBy('kode_klasifikasi_rincian_output', 'asc')->get());
        }

        if ($request->kak6_ro) {
            $pok_awal = POK::where('id', $request->kak6_ro)->first();
            $query->where('kode_rincian_output', $pok_awal->kode_rincian_output);
        } else {
            return response()->json($query->whereNull('kode_komponen')->whereNotNull('kode_rincian_output')
                ->orderBy('kode_rincian_output', 'asc')->get());
        }

        if ($request->kak6_komponen) {
            $pok_awal = POK::where('id', $request->kak6_komponen)->first();
            $query->where('kode_komponen', $pok_awal->kode_komponen);
        } else {
            return response()->json($query->whereNull('kode_sub_komponen')->whereNotNull('kode_komponen')
                ->orderBy('kode_komponen', 'asc')->get());
        }

        if ($request->kak6_sub_komponen) {
            $pok_awal = POK::where('id', $request->kak6_sub_komponen)->first();
            $query->where('kode_sub_komponen', $pok_awal->kode_sub_komponen);
        } else {
            return response()->json($query->whereNull('kode_akun')->whereNotNull('kode_sub_komponen')
                ->orderBy('kode_sub_komponen', 'asc')->get());
        }

        return response()->json($query->orderBy('kode_akun', 'asc')->whereNotNull('kode_akun')->get());
    }
}
