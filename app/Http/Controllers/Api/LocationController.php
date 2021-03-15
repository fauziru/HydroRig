<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class LocationController extends APIBaseController
{
    public function indexProvinsi(){
      $provinsi = Provinsi::all();
      return $this->sendResponse($provinsi);
    }

    public function indexKabupaten(){
      $kabupaten = Kabupaten::with('provinsi:id,name_provinsi')->get();
      return $this->sendResponse($kabupaten);
    }

    public function indexKecamatan(){
      $kecamatan = Kecamatan::with('kabupaten:id,name_kabupaten')->get();
      return $this->sendResponse($kecamatan);
    }

    public function indexKelurahan(){
      $kelurahan = Kelurahan::with('kecamatan:id,name_kecamatan')->get();
      return $this->sendResponse($kelurahan);
    }

    public function detailProvinsi(Request $request, $id) {
      $provinsi = Provinsi::findOrFail($id);
      $kabupaten = $provinsi->kabupaten->where('provinsi_id', $id)->all();
      return $this->sendResponse($kabupaten);
    }

    public function detailKabupaten(Request $request, $id) {
      $kabupaten = Kabupaten::findOrFail($id);
      $kecamatan = $kabupaten->kecamatan->where('kabupaten_id', $id)->all();
      return $this->sendResponse($kecamatan);
    }

    public function detailKecamatan(Request $request, $id) {
      $kecamatan = Kecamatan::findOrFail($id);
      $kelurahan = $kecamatan->kelurahan->where('kecamatan_id', $id)->all();
      return $this->sendResponse($kelurahan);
    }


}
