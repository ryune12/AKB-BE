<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        if (!Auth::attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }
        $user = Auth::user();
        if ($user->deleted_at != null) {
            return response(['message' => 'Akun Blocked'], 401);
        }


        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }

    public function index()
    {
        $karyawan = DB::table('users')->get();
        if (count($karyawan)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $karyawan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $karyawan = User::find($id);
        if (!is_null($karyawan)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $karyawan
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
            'nama' => 'required|alpha',
            'alamat' => 'required',
        ]);

        $storeData['password'] = bcrypt($request->password);
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $karyawan = User::create($storeData);
        return response([
            'message' => 'Add Karyawan Success',
            'data' => $karyawan,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);
        if (is_null($karyawan)) {
            return response([
                'message' => 'Karyawan Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();

        if (isset($updateData['role_id'])) {
            $karyawan->role_id = $updateData['role_id'];
        }

        if (isset($updateData['nama'])) {
            $karyawan->nama = $updateData['nama'];
        }

        if (isset($updateData['telp'])) {
            $karyawan->telp = $updateData['telp'];
        }

        if (isset($updateData['alamat'])) {
            $karyawan->alamat = $updateData['alamat'];
        }

        if (isset($updateData['username'])) {
            $karyawan->username = $updateData['username'];
        }
        if (isset($updateData['password'])) {
            $karyawan->password = bcrypt($updateData['password']);
        }
        $karyawan->updated_at = Carbon::now();

        if ($karyawan->save()) {
            return response([
                'message' => 'Update User Success',
                'data' => $karyawan,
            ], 200);
        }
        return response([
            'message' => 'Update User Failed',
            'data' => null,
        ], 400);
    }

    public function deactivateAcc(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->updated_at = Carbon::now();
        $karyawan->deleted_at = Carbon::now();
        if ($karyawan->save()) {
            return response([
                'message' => 'Update User Success',
                'data' => $karyawan,
            ], 200);
        }
        return response([
            'message' => 'Update User Failed',
            'data' => null,
        ], 400);
    }
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
