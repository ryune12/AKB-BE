<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $Menu = Menu::all();
        if (count($Menu)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Menu
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function kategoriMenu($kategori)
    {
        $Menu = DB::table('menus')
            ->leftJoin('bahans', 'menus.id', '=', 'bahans.id_menu')
            ->select('menus.*', 'bahans.jumlah', 'bahans.serving_size')
            ->where('menus.kategori', '=', $kategori)
            ->where('bahans.deleted_at', '=', null)
            ->where('menus.deleted_at', '=', null)
            ->get('*');

        if (count($Menu)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Menu
            ], 200);
        } else if (count($Menu) == 0) {
            $Menu1 = DB::table('menus')
                ->where('menus.kategori', '=', $kategori)->get('*');
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Menu1
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $Menu = DB::table('menus')
            ->leftJoin('bahans', 'menus.id', '=', 'bahans.id_menu')
            ->select('menus.*', 'bahans.jumlah', 'bahans.serving_size')
            ->where('menus.id', '=', $id)
            ->where('bahans.deleted_at', '=', null)
            ->where('menus.deleted_at', '=', null)
            ->get('*');
        if (!is_null($Menu)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $Menu
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {

        $storeData = $request->except('image');
        $validate = Validator::make($storeData, [
            'nama_menu' => 'required',
            'harga' => 'required',
            'kategori' => 'required',
            'unit' => 'required',
            'deskripsi' => 'required',
        ]);



        if (isset($request->image)) {
            $explodeed = explode(',', $request->image);
            $decoded = base64_decode($explodeed[1]);

            (str_contains($explodeed[0], 'jpeg')) ?  $extension = 'jpg' : $extension = 'png';
            $filename = rand() . '.' . $extension;
            $path = public_path() . '/' . $filename;
            file_put_contents($path, $decoded);
        }
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Menu = Menu::create($request->except('image') + [
            'image' => $filename
        ]);
        return response([
            'message' => 'Add Menu Success',
            'data' => $Menu,
        ], 200);
    }

    public function destroy($id)
    {
        $Menu = Menu::find($id);

        if (is_null($Menu)) {
            return response([
                'message' => 'Menu Not Found',
                'data' => null
            ], 404);
        }
        $Menu->deleted_at = Carbon::now();
        if ($Menu->save()) {
            return response([
                'message' => 'Delete Menu Success',
                'data' => $Menu,
            ], 200);
        }

        return response([
            'message' => 'Delete Menu Failed',
            'data' => null,
        ], 400);
    }

    public function updateFoto(Request $request)
    {
        // \Log::info($request->all());
        $updateData = $request->all();
        $Menu = Menu::findOrFail($updateData['id']);
        if (is_null($Menu)) {
            return response([
                'message' => 'Menu Not Found',
                'data' => null
            ], 404);
        }
        if (isset($request->image)) {
            $explodeed = explode(',', $request->image);
            $decoded = base64_decode($explodeed[1]);

            (str_contains($explodeed[0], 'jpeg')) ?  $extension = 'jpg' : $extension = 'png';
            $filename = rand() . '.' . $extension;
            $path = public_path() . '/' . $filename;
            file_put_contents($path, $decoded);
            $Menu->image = $filename;
        }

        $Menu->updated_at = Carbon::now();

        if ($Menu->save()) {
            return response([
                'message' => 'Update Menu Success',
                'data' => $Menu,
            ], 200);
        }
        return response([
            'message' => 'Update Menu Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        // \Log::info($request->all());
        $updateData = $request->all();
        $Menu = Menu::findOrFail($id);
        if (is_null($Menu)) {
            return response([
                'message' => 'Menu Not Found',
                'data' => null
            ], 404);
        }
        if (isset($updateData['nama_menu'])) {
            $Menu->nama_menu = $updateData['nama_menu'];
        }

        if (isset($updateData['harga'])) {
            $Menu->harga = $updateData['harga'];
        }

        if (isset($updateData['kategori'])) {
            $Menu->kategori = $updateData['kategori'];
        }

        if (isset($updateData['unit'])) {
            $Menu->unit = $updateData['unit'];
        }
        if (isset($updateData['deskripsi'])) {
            $Menu->deskripsi = $updateData['deskripsi'];
        }
        if (isset($request->image)) {
            $explodeed = explode(',', $request->image);
            $decoded = base64_decode($explodeed[1]);

            (str_contains($explodeed[0], 'jpeg')) ?  $extension = 'jpg' : $extension = 'png';
            $filename = rand() . '.' . $extension;
            $path = public_path() . '/' . $filename;
            file_put_contents($path, $decoded);
            $Menu->image = $filename;
        }

        $Menu->updated_at = Carbon::now();

        if ($Menu->save()) {
            return response([
                'message' => 'Update Menu Success',
                'data' => $Menu,
            ], 200);
        }
        return response([
            'message' => 'Update Menu Failed',
            'data' => null,
        ], 400);
    }
}
