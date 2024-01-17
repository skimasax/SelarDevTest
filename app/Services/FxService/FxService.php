<?php

namespace App\Services\FxService;


use App\Models\FlutterRate;
use App\Traits\Paystack;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;


class FxService
{
   
    
    public function storeRate($request)
    {

        $input['creator_currency']    = $request->input('creator_currency');
        $input['buyer_currency']    = $request->input('buyer_currency');
        $input['amount']    = $request->input('amount');


       $rate= FlutterRate::where('from',$input['creator_currency'])
        ->where('to',$input['buyer_currency'])
        ->first();

        //add company markup of 5%...This can be done dynamically but in this case since it a test and we have the percentage given

        $flwRate = $rate->flw_rate;
        $markupPercentage = 0.05;
        $markup = $flwRate * $markupPercentage;
        $newRate = $flwRate + $markup;

        return $newRate;
    }

    public function payoutRate($sendingCountry,$receivingCountry,$amount)
    {
        $rate= FlutterRate::where('from',$sendingCountry)
        ->where('to',$receivingCountry)
        ->first();

        //add company markup of 5%...This can be done dynamically but in this case since it a test and we have the percentage given

        $flwRate = $rate->flw_rate;
        $markup = 0.05;
        $newRate = $flwRate * $markup;

        return $newRate;
    }


}
