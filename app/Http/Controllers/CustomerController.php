<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function get_all_customer() {

        $customers = Customer::orderBy('id','DESC')->get();
        return response()->json([
            'customers' => $customers
        ], 200);
    }
}
