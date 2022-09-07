<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Bahan;
use App\IncomingStocks;
use App\WasteStocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BahanController extends Controller
{
    public function index()
    {
        $Bahan = DB::table('bahans')
            ->join('menus', 'bahans.id_menu', '=', 'menus.id')
            ->select('bahans.*', 'menus.nama_menu', 'menus.kategori')
            ->where('bahans.deleted_at', '=', null)
            ->get();
        if (count($Bahan)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Bahan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $Bahan = Bahan::find($id);
        if (!is_null($Bahan)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $Bahan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_bahan' => 'required',
            'serving_size' => 'required',
            'id_menu' => 'required',
            'rasio_konversi' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Bahan = Bahan::create($storeData);
        return response([
            'message' => 'Add Bahan Success',
            'data' => $Bahan,
        ], 200);
    }

    public function destroy($id)
    {
        $Bahan = Bahan::find($id);

        if (is_null($Bahan)) {
            return response([
                'message' => 'Bahan Not Found',
                'data' => null
            ], 404);
        }
        $Bahan->deleted_at = Carbon::now();

        if ($Bahan->update()) {
            return response([
                'message' => 'Delete Bahan Success',
                'data' => $Bahan,
            ], 200);
        }

        return response([
            'message' => 'Delete Bahan Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        $Bahan = Bahan::findOrFail($id);
        if (is_null($Bahan)) {
            return response([
                'message' => 'Bahan Not Found',
                'data' => null
            ], 404);
        }
        if (isset($updateData['nama_bahan'])) {
            $Bahan->nama_bahan = $updateData['nama_bahan'];
        }

        if (isset($updateData['serving_size'])) {
            $Bahan->serving_size = $updateData['serving_size'];
        }

        $Bahan->updated_at = Carbon::now();

        if ($Bahan->save()) {
            return response([
                'message' => 'Update Bahan Success',
                'data' => $Bahan,
            ], 200);
        }
        return response([
            'message' => 'Update Bahan Failed',
            'data' => null,
        ], 400);
    }

    public function addStock($id, $jumlah)
    {
        $Bahan = Bahan::findOrFail($id);
        $konversi = $jumlah * $Bahan->rasio_konversi;
        $Bahan->jumlah +=  $konversi;
        $Bahan->updated_at = Carbon::now();
        $Bahan->update();
    }

    public function updateStock($id, $jumlah)
    {
        $IncomingStock = IncomingStocks::findOrFail($id);
        $Bahan = Bahan::findOrFail($IncomingStock->id_bahan);

        $Bahan->jumlah -= ($IncomingStock->jumlah * $Bahan->rasio_konversi);
        $Bahan->jumlah += ($jumlah * $Bahan->rasio_konversi);
        $Bahan->updated_at = Carbon::now();
        $Bahan->update();
    }

    public function deleteStock($data)
    {
        $Bahan = Bahan::findOrFail($data->id_bahan);
        $Bahan->jumlah -= ($data->jumlah * $Bahan->rasio_konversi);
        $Bahan->updated_at = Carbon::now();
        $Bahan->update();
    }

    public function addWaste($id, $jumlah)
    {
        $Bahan = Bahan::findOrFail($id);
        $Bahan->jumlah -=  $jumlah;
        $Bahan->updated_at = Carbon::now();
        $Bahan->update();
    }

    public function updateWaste($id, $jumlah)
    {
        $Waste = WasteStocks::findOrFail($id);
        $Bahan = Bahan::findOrFail($Waste->id_bahan);
        if ($Bahan->jumlah < $jumlah) {
            return response([
                'message' => 'Bahan is not enough',
                'data' => null,
            ], 400);
        }

        $Bahan->jumlah += $Waste->jumlah;
        $Bahan->jumlah -= $jumlah;
        $Bahan->updated_at = Carbon::now();

        $Bahan->update();
    }
    public function deleteWaste($data)
    {
        $Bahan = Bahan::findOrFail($data->id_bahan);
        $Bahan->jumlah += $data->jumlah;
        $Bahan->updated_at = Carbon::now();
        $Bahan->update();
    }
}
