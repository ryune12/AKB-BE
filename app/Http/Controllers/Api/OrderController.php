<?php

namespace App\Http\Controllers\Api;

use App\DetailOrder;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Order;
use App\Bahan;
use App\CardInfo;
use App\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $order = DB::table('orders')
            ->join('reservasis', 'orders.id_reservasi', '=', 'reservasis.id')
            ->join('mejas', 'mejas.id', '=', 'reservasis.id_meja')
            ->select(
                'orders.*',
                'reservasis.id as id_reservasi',
                'reservasis.tanggal_reservasi as tanggal_reservasi',
                'reservasis.sesi as sesi',
                'reservasis.status as status',
                'mejas.id as id_meja',
                'mejas.nomor_meja as nomor_meja'
            )
            ->get();

        if (count($order)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $order
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function QueueOrder()
    {
        $tanggal = Carbon::now()->format('Y-m-d');
        $pesanan = DB::table('detail_orders')
            ->join('menus', 'detail_orders.id_menu', '=', 'menus.id')
            ->join('orders', 'orders.id', '=', 'detail_orders.id_order')
            ->join('reservasis', 'orders.id_reservasi', '=', 'reservasis.id')
            ->select(
                'detail_orders.*',
                'menus.nama_menu as nama_menu',
                'menus.unit as unit'
            )
            ->where('status_pesanan', '=', '0')
            ->where('tanggal_reservasi', '=', $tanggal)
            ->orderBy('created_at', 'ASC')
            ->get();

        if (count($pesanan)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pesanan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => []
        ], 200);
    }

    public function ReadyOrder()
    {
        $tanggal = Carbon::now()->format('Y-m-d');
        $pesanan = DB::table('detail_orders')
            ->join('menus', 'detail_orders.id_menu', '=', 'menus.id')
            ->join('orders', 'orders.id', '=', 'detail_orders.id_order')
            ->join('reservasis', 'orders.id_reservasi', '=', 'reservasis.id')
            ->select(
                'detail_orders.*',
                'menus.nama_menu as nama_menu',
                'menus.unit as unit'
            )
            ->where('tanggal_reservasi', '=', $tanggal)
            ->where('status_pesanan', '=', '1')
            ->orderBy('updated_at', 'ASC')
            ->get();

        if (count($pesanan)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pesanan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => []
        ], 200);
    }

    public function updateStatus(Request $request)
    {
        $post = $request->all();
        $data = DetailOrder::find($post['id']);
        if (is_null($data)) {
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ], 404);
        }


        $data->status_pesanan++;
        $data->updated_at = Carbon::now();

        if ($data->update()) {
            return response([
                'message' => 'Update Reservasi Success',
                'data' => $data,
            ], 200);
        }
        return response([
            'message' => 'Update Reservasi Failed',
            'data' => null,
        ], 400);
    }

    public function getCashier($id)
    {
        $cashier = DB::table('users')
            ->where('users.id', '=', $id)
            ->get();
        if (count($cashier)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $cashier
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => []
        ], 200);
    }

    public function getWaiter($id)
    {
        $waiter = DB::table('reservasis')
            ->join('users', 'users.id', '=', 'reservasis.id_karyawan')
            ->select(
                'users.nama as nama',
            )
            ->where('reservasis.id', '=', $id)
            ->get();
        if (count($waiter)  > 0) {
            $return = response([
                'message' => 'Retrieve All Success',
                'data' => $waiter
            ], 200);
            return $return;
        }

        return response([
            'message' => 'Empty',
            'data' => []
        ], 200);
    }

    public function show($id)
    {
        Log::info($id);
        $order = DB::table('orders')
            ->join('reservasis', 'reservasis.id', '=', 'orders.id_reservasi')
            ->select('orders.*', 'reservasis.status')
            ->where('orders.id_reservasi', $id)->get();
        if (!is_null($order)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $order
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $storeData = $request->all();
        $storeData['created_at'] = Carbon::now();
        $reservasi = Reservasi::find($storeData['id_reservasi']);
        $reservasi->status = "Reserved";
        $reservasi->save();
        $order = Order::create($storeData);

        $respons = response([
            'message' => 'Add Menu Success',
            'data' => array(
                $order
            ),
        ], 200);
        Log::info($respons);
        return $respons;
    }

    public function end($id)
    {
        $reservasi = Reservasi::find($id);
        $reservasi->status = "Completed";
        $reservasi->save();
        $respons = response([
            'message' => 'End Reservasi Success',
            'data' => array(
                $reservasi
            ),
        ], 200);
        return $respons;
    }

    public function getDetail($id)
    {
        // $menu = DB::table('detail_orders')->distinct()
        //     ->join('menus', 'menus.id', '=', 'detail_orders.id_menu')
        //     ->where('detail_orders.id_order', '=', $id)
        //     ->get();
        $order = DB::table('detail_orders')
            ->join('menus', 'menus.id', '=', 'detail_orders.id_menu')
            ->selectRaw(
                "
                menus.nama_menu,
                sum(detail_orders.kuantitas) as kuantitas,
                sum(detail_orders.subtotal) as subtotal
                "
            )
            ->groupBy('menus.nama_menu')
            ->where('detail_orders.id_order', '=', $id)
            ->get();
        $respons = response([
            'message' => 'Add detail Success',
            'data' => $order
        ], 200);
        Log::info($respons);

        return $respons;
    }

    public function getCard()
    {
        $kredit = CardInfo::where('tipe_kartu', '=', 'Kredit')->get();
        $debit = CardInfo::where('tipe_kartu', '=', 'Debit')->get();
        $respons = response([
            'message' => 'Add detail Success',
            'kredit' => $kredit,
            'debit' => $debit
        ], 200);
        return $respons;
    }

    public function detailCreate(Request $request)
    {
        Log::info($request->all());
        $storeData = $request->all();
        $bahan = Bahan::where('id_menu', '=', $storeData['id_menu'])->first();

        $jumlah = $storeData['kuantitas'] * $bahan->serving_size;
        $bahan->jumlah -= $jumlah;

        $detail = DetailOrder::create($storeData);
        $bahan->save();
        $respons = response([
            'message' => 'Add detail Success',
            'data' => array(
                $detail
            ),
        ], 200);


        Log::info($respons);
        Log::info($bahan);
        return $respons;
    }

    public function OrderDetail($id)
    {
        $order = DB::table('detail_orders')
            ->join('menus', 'menus.id', '=', 'detail_orders.id_menu')
            ->selectRaw(
                "
                detail_orders.id,
                menus.nama_menu,
                menus.harga as harga,
                sum(detail_orders.kuantitas) as kuantitas,
                sum(detail_orders.subtotal) as subtotal
                "
            )
            ->groupBy('menus.nama_menu')
            ->where('detail_orders.id_order', '=', $id)
            ->get();
        $reservasi = DB::table('orders')
            ->join('reservasis', 'reservasis.id', '=', 'orders.id_reservasi')
            ->where('orders.id', '=', $id)
            ->first();

        $meja = DB::table('reservasis')
            ->join('mejas', 'mejas.id', '=', 'reservasis.id_meja')
            ->where('reservasis.id', '=', $reservasi->id_reservasi)
            ->first();
        $customer = DB::table('customers')
            ->where('id', '=', $reservasi->id_customer)->first();
        if ($reservasi->id_kartu != null) {
            $card = DB::table('card_infos')
                ->where('no_kartu', '=', $reservasi->id_kartu)->first();
        } else {
            $card = null;
        }

        if (count($order)  > 0) {
            $respons = response([
                'message' => 'Retrieve All Success',
                'data' => $order,
                'reservasi' => $reservasi,
                'meja' => $meja,
                'customer' => $customer,
                'card' => $card,
            ], 200);
            return $respons;
        } else {
            $respons = response([
                'message' => 'Retrieve All Success',
                'data' => $order,
                'reservasi' => $reservasi,
                'meja' => $meja
            ], 200);
            Log::info($respons);
            return $respons;
        }

        return response([
            'message' => 'Empty',
            'data' => []
        ], 200);
    }
    public function cash(Request $request, $id)
    {
        $post = $request->all();
        $data = Order::find($id);
        if (is_null($data)) {
            return response([
                'message' => 'Order Not Found',
                'data' => null
            ], 404);
        }

        $data->id_karyawan = $post['id_karyawan'];
        $data->total = $post['total'];
        $data->total_qty = $post['total_qty'];
        $data->total_item = $post['total_item'];
        $data->tax = $post['tax'];
        $data->services = $post['services'];
        $data->cash = $post['cash'];
        $data->jenis_pembayaran = $post['jenis_pembayaran'];
        $data->status_pembayaran = $post['status_pembayaran'];
        $data->updated_at = Carbon::now();

        if ($data->update()) {
            return response([
                'message' => 'Pembayaran Success',
                'data' => $data,
            ], 200);
        }
        return response([
            'message' => 'Pembayaran Failed',
            'data' => null,
        ], 400);
    }
    public function debit(Request $request, $id)
    {
        $post = $request->all();
        $card = CardInfo::find($post['no_kartu']);
        Log::info($card);
        if (empty($card)) {
            $card = [
                "no_kartu" => $post['no_kartu'],
                "tipe_kartu" => "Debit",
            ];
            CardInfo::create($card);
        }
        $data = Order::find($id);
        if (is_null($data)) {
            return response([
                'message' => 'Order Not Found',
                'data' => null
            ], 404);
        }

        $data->id_karyawan = $post['id_karyawan'];
        $data->total = $post['total'];
        $data->total_qty = $post['total_qty'];
        $data->total_item = $post['total_item'];
        $data->tax = $post['tax'];
        $data->services = $post['services'];
        $data->id_kartu = $post['no_kartu'];
        $data->jenis_pembayaran = $post['jenis_pembayaran'];
        $data->status_pembayaran = $post['status_pembayaran'];
        $data->updated_at = Carbon::now();

        if ($data->update()) {
            return response([
                'message' => 'Pembayaran Success',
                'data' => $data,
            ], 200);
        }
        return response([
            'message' => 'Pembayaran Failed',
            'data' => null,
        ], 400);
    }
    public function kredit(Request $request, $id)
    {
        $post = $request->all();
        $card = CardInfo::find($post['no_kartu']);
        Log::info($card);
        if (empty($card)) {
            $card = [
                "no_kartu" => $post['no_kartu'],
                "tipe_kartu" => "Kredit",
                "exp_date" => $post['valid_date'],
                "nama_pemilik" => $post['nama_pemilik'],
            ];
            CardInfo::create($card);
        }
        $data = Order::find($id);
        if (is_null($data)) {
            return response([
                'message' => 'Order Not Found',
                'data' => null
            ], 404);
        }

        $data->id_karyawan = $post['id_karyawan'];
        $data->total = $post['total'];
        $data->total_qty = $post['total_qty'];
        $data->total_item = $post['total_item'];
        $data->tax = $post['tax'];
        $data->kode_verifikasi = $post['cvv'];
        $data->services = $post['services'];
        $data->id_kartu = $post['no_kartu'];
        $data->jenis_pembayaran = $post['jenis_pembayaran'];
        $data->status_pembayaran = $post['status_pembayaran'];
        $data->updated_at = Carbon::now();

        if ($data->update()) {
            return response([
                'message' => 'Pembayaran Success',
                'data' => $data,
            ], 200);
        }
        return response([
            'message' => 'Pembayaran Failed',
            'data' => null,
        ], 400);
    }
}