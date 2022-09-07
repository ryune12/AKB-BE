<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Customer;
use App\Meja;
use App\User;
use Illuminate\Support\Facades\Log;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasi = DB::table('reservasis')->distinct()
            ->join('mejas', 'mejas.id', '=', 'reservasis.id_meja')
            ->select('reservasis.*', 'mejas.nomor_meja as nomor_meja')
            ->orderBy('tanggal_reservasi', 'ASC')
            ->get();
        if (count($reservasi)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reservasi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        Log::info($id);
        $reservasi = DB::table('reservasis')
            ->join('customers', 'customers.id', '=', 'reservasis.id_customer')
            ->join('mejas', 'mejas.id', '=', 'reservasis.id_meja')
            ->select('reservasis.*', 'mejas.nomor_meja as nomor_meja', 'customers.id as id_customer', 'customers.nama as nama', 'customers.email as email', 'customers.no_telp as no_telp')
            ->where('reservasis.id', '=', $id)->first();
        if (!is_null($reservasi)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $reservasi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function cancelReservasi($id)
    {
        // \Log::info($id);
        $reservasi = Reservasi::findOrFail($id);
        if (is_null($reservasi)) {
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ], 404);
        }
        $reservasi->deleted_at = Carbon::now();
        if ($reservasi->save()) {
            return response([
                'message' => 'Cancel Reservasi Success',
                'data' => $reservasi,
            ], 200);
        }

        return response([
            'message' => 'Cancel Reservasi Failed',
            'data' => null,
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        Log::info($request->all());

        $data = $this->createCustomer($storeData);

        return response([
            'message' => 'Add Reservasi Success',
            'data' => $data,
        ], 200);
    }

    public function createCustomer($storeData)
    {
        if (($storeData['nama']) != null && ($storeData['email']) != null && isset($storeData['no_telp']) != null) {
            $data['nama'] = $storeData['nama'];
            $data['email'] = $storeData['email'];
            $data['no_telp'] = $storeData['no_telp'];
            $validate = Validator::make($data, [
                'nama' => 'required',
                'email' => "required|unique:customers,email,NULL,id,deleted_at,NULL",
                'no_telp' => "required|numeric|unique:customers,no_telp,NULL,id,deleted_at,NULL",
            ]);
            if ($validate->fails())
                return response(['message' => $validate->errors()], 400);
            $data['created_at'] = Carbon::now();
            Customer::create($data);
        }
        return $this->createReservasi($storeData);
    }

    public function createReservasi($data)
    {
        $meja = Meja::where('nomor_meja', '=', $data['nomor_meja'])->first();
        $customer = Customer::where('no_telp', $data['no_telp'])->first();
        $reservasi = [
            'sesi' => $data['sesi'],
            'tanggal_reservasi' => $data['tanggal_reservasi'],
            'status' => 'Pending',
            'id_customer' => $customer->id,
            'id_meja' => $meja['id'],
            'id_karyawan' => $data['id_karyawan'],
            'created_at' => Carbon::now(),
        ];
        $data =  Reservasi::create($reservasi);
        return $data;
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::find($id);
        $data = $request->all();
        if (is_null($reservasi)) {
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ], 404);
        }
        $meja = Meja::where('nomor_meja', '=', $data['nomor_meja'])->first();

        $reservasi->id_meja = $meja->id;
        $reservasi->updated_at = Carbon::now();

        if (DB::table('reservasis')->where('id', $id)->update($request->except(['nomor_meja']))) {
            return response([
                'message' => 'Update Reservasi Success',
                'data' => $reservasi,
            ], 200);
        }
        return response([
            'message' => 'Update Reservasi Failed',
            'data' => null,
        ], 400);
    }
    public function cetak($id, $id_user)
    {
        Log::info($id, $id_user);
        $user = User::find($id_user);
        return view('cetak', [
            'data' => $id
        ]);
    }
}
