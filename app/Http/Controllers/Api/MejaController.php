<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MejaController extends Controller
{
    public function index($tanggal = null, $sesi = null)
    {
        $start = '17:00:00';
        if ($tanggal == null) {
            $tanggal = Carbon::now()->format('Y-m-d');
        } else {
            $tanggal = urldecode($tanggal);
        }
        $time = Carbon::now()->format('H:i:s');
        if ($sesi == null) {
            $sesi =  ($time <= $start) ? "1" : "2";
        } else {
            $sesi = urldecode($sesi);
        }

        $reservasi = DB::table('reservasis')->where('reservasis.tanggal_reservasi', '=', "$tanggal")
            ->where('reservasis.sesi', '=', "$sesi")
            ->where('reservasis.status', '!=', "Completed");

        $meja = DB::table('mejas')->distinct()
            ->selectRaw("
                mejas.id as `id_meja`,
                mejas.nomor_meja as `nomor_meja`,
                mejas.status as `aktif`,
                reservasis.tanggal_reservasi,
                if(
                    date(reservasis.tanggal_reservasi) = '$tanggal' AND reservasis.sesi = '$sesi' AND STRCMP(reservasis.status, 'Completed') = 1,'Booked','Ready'
                ) as 'status'")
            ->leftJoinSub($reservasi, 'reservasis', function ($join) {
                $join->on('reservasis.id_meja', '=', 'mejas.id');
            })
            ->where('reservasis.deleted_at', '=', null)
            ->orderBy('nomor_meja', 'ASC')->get();
        $respons = response([
            'message' => 'Retrieve All Success',
            'data' => $meja
        ], 200);
        Log::info($respons);
        return $respons;
    }

    public function getDetailMeja($id)
    {
        $detail = DB::table('reservasis')
            ->join('users', 'users.id', '=', 'reservasis.id_karyawan')
            ->join('customers', 'customers.id', '=', 'reservasis.id_customer')
            ->select('reservasis.*', 'customers.nama as nama_customer', 'users.nama as nama_karyawan')
            ->where('id_meja', $id)
            ->where('status', 'Reserved')
            ->first();
        if (!is_null($detail)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }



    public function statusMeja(Request $request)
    {
        if ($request['tanggal_reservasi'] == null)
            $tanggal = Carbon::now();
        else
            $tanggal = $request['tanggal_reservasi'];
        if ($request['sesi'] == null) {
            $time = Carbon::now()->format('H:i:s');
            if ($time <= '16:00:00') {
                $sesi = 1;
            } else {
                $sesi = 2;
            }
        } else $sesi = $request['sesi'];

        $Meja = DB::table('reservasi')->select('id_meja', 'status')
            ->where('tanggal_reservasi', $tanggal)
            ->where('sesi', $sesi)
            ->get();
        if (!is_null($Meja)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $Meja
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $Meja = DB::table('mejas')->where('nomor_meja', $id)->first();
        if (!is_null($Meja)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $Meja
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
            'nomor_meja' => 'required|numeric|unique:Mejas',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Meja = Meja::create($storeData);
        return response([
            'message' => 'Add Meja Success',
            'data' => $Meja,
        ], 200);
    }

    public function destroy($id)
    {
        $Meja = Meja::find($id);

        if (is_null($Meja)) {
            return response([
                'message' => 'Meja Not Found',
                'data' => null
            ], 404);
        }
        $Meja->deleted_at = Carbon::now();
        if ($Meja->update()) {
            return response([
                'message' => 'Delete Meja Success',
                'data' => $Meja,
            ], 200);
        }

        return response([
            'message' => 'Delete Meja Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $Meja = Meja::find($id);
        if (is_null($Meja)) {
            return response([
                'message' => 'Meja Not Found',
                'data' => null
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            'nomor_meja' => 'numeric|unique:Mejas',
        ]);
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Meja->updated_at = Carbon::now();

        if (DB::table('mejas')->where('id', $id)->update($request->all())) {
            return response([
                'message' => 'Update Meja Success',
                'data' => $Meja,
            ], 200);
        }
        return response([
            'message' => 'Update Meja Failed',
            'data' => null,
        ], 400);
    }
}
