<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\IncomingStocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class IncomingController extends Controller
{
    protected $BahanController;
    public function __construct(BahanController $BahanController)
    {
        $this->BahanController = $BahanController;
    }

    public function index()
    {
        $Incoming = DB::table('incoming_stocks')->join('bahans', 'bahans.id', '=', 'incoming_stocks.id_bahan')
            ->select('incoming_stocks.*', 'bahans.nama_bahan', 'bahans.unit')
            ->where('incoming_stocks.deleted_at', '=', null)
            ->get();
        if (count($Incoming)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Incoming
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $Bahan = IncomingStocks::find($id);
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
            'harga' => 'required',
            'jumlah' => 'required',
            'id_bahan' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $Incoming = IncomingStocks::create($storeData);
        $this->BahanController->addStock($storeData['id_bahan'], $storeData['jumlah']);
        return response([
            'message' => 'Add Bahan Success',
            'data' => $Incoming,
        ], 200);
    }

    public function destroy($id)
    {
        $Bahan = IncomingStocks::find($id);

        if (is_null($Bahan)) {
            return response([
                'message' => 'Bahan Not Found',
                'data' => null
            ], 404);
        }
        $Bahan->deleted_at = Carbon::now();
        $this->BahanController->deleteStock($Bahan);
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
        $Stok = IncomingStocks::findOrFail($id);
        if (is_null($Stok)) {
            return response([
                'message' => 'Stok Not Found',
                'data' => null
            ], 404);
        }

        if (isset($updateData['harga'])) {
            $Stok->harga = $updateData['harga'];
        }

        if (isset($updateData['jumlah'])) {
            $Stok->jumlah = $updateData['jumlah'];
        }

        $Stok->updated_at = Carbon::now();
        $this->BahanController->updateStock($id, $updateData['jumlah']);

        if ($Stok->save()) {
            return response([
                'message' => 'Update Stok Success',
                'data' => $Stok,
            ], 200);
        }
        return response([
            'message' => 'Update Stok Failed',
            'data' => null,
        ], 400);
    }
}
