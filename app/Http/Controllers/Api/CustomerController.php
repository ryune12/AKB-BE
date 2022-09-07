<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    public function index()
    {
        $Customer = Customer::where('deleted_at', '=', null)->get();
        if (count($Customer)  > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $Customer
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
            'nama' => 'required',
            'email' => "required|unique:customers,email,NULL,id,deleted_at,NULL",
            'no_telp' => "required|numeric|unique:customers,no_telp,NULL,id,deleted_at,NULL",
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Customer = Customer::create($storeData);
        return response([
            'message' => 'Add Customer Success',
            'data' => $Customer,
        ], 200);
    }

    public function show($id)
    {
        $Customer = Customer::find($id);
        if (!is_null($Customer)  > 0) {
            return response([
                'message' => 'Retrieve Success',
                'data' => $Customer
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $Customer = Customer::find($id);

        if (is_null($Customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }
        $Customer->deleted_at = Carbon::now();
        if ($Customer->update()) {
            return response([
                'message' => 'Delete Customer Success',
                'data' => $Customer,
            ], 200);
        }

        return response([
            'message' => 'Delete Customer Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        // \Log::info($request->all());
        $Customer = Customer::find($id);
        if (is_null($Customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => "required|unique:customers,email,$id,id,deleted_at,NULL",
            'no_telp' => "required|numeric|unique:customers,no_telp,$id,id,deleted_at,NULL",
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $Customer->updated_at = Carbon::now();

        if ($Customer->update($request->all())) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $Customer,
            ], 200);
        }
        return response([
            'message' => 'Update Customer Failed',
            'data' => null,
        ], 400);
    }
}
