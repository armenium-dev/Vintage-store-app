<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyController{

    public function shop1Webhook(Request $request){

        Log::stack(['webhook'])->debug($request);


        return response()->json(['status' => 200]);
    }

}
