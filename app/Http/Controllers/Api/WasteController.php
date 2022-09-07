<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\WasteStocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class WasteController extends Controller
{
    protected $BahanController;
    public function __construct(BahanController $BahanController)
    {
        $this->BahanController = $BahanController;
    }

    public function index()
    {
        $Waste = DB::table('waste_stocks')->join('bahans', 'bahans.id', '=', 'waste_stocks.id_bahan')
            ->select('waste_stocks.*', 'bahans.nama_bahan', 'bahans.unit', 'bahans.jumlah as bahan_jumlah')
            ->where('waste_stocks.deleted_at', '=', null)
            ->get();
        if (count($Waste)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Waste
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $Bahan = WasteStocks::find($id);
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
            'jumlah' => 'required',
            'id_bahan' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Waste = WasteStocks::create($storeData);
        $this->BahanController->addWaste($storeData['id_bahan'], $storeData['jumlah']);
        return response([
            'message' => 'Add Bahan Success',
            'data' => $Waste,
        ], 200);
    }

    public function destroy($id)
    {
        $Bahan = WasteStocks::find($id);

        if (is_null($Bahan)) {
            return response([
                'message' => 'Bahan Not Found',
                'data' => null
            ], 404);
        }
        $Bahan->deleted_at = Carbon::now();
        $this->BahanController->deleteWaste($Bahan);
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
        $Waste = WasteStocks::findOrFail($id);
        if (is_null($Waste)) {
            return response([
                'message' => 'Waste Not Found',
                'data' => null
            ], 404);
        }

        if (isset($updateData['jumlah'])) {
            $Waste->jumlah = $updateData['jumlah'];
        }

        $Waste->updated_at = Carbon::now();
        $this->BahanController->updateWaste($id, $updateData['jumlah']);

        if ($Waste->save()) {
            return response([
                'message' => 'Update Waste Success',
                'data' => $Waste,
            ], 200);
        }
        return response([
            'message' => 'Update Waste Failed',
            'data' => null,
        ], 400);
    }
}
