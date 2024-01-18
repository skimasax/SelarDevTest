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
        $input['creator_selling_price_in_creator_curreny']    = $request->input('creator_selling_price_in_creator_curreny');


       $rate= FlutterRate::where('from',$input['buyer_currency'])
        ->where('to',$input['creator_currency'])
        ->first();

        //add company markup of 5%...This can be done dynamically but in this case since it a test and we have the percentage given

        $flwRate = $rate->flw_rate;
        $markupPercentage = 0.05;
        $markup = $flwRate * $markupPercentage;
        $newRate = $flwRate + $markup;

        return $newRate * $input['creator_selling_price_in_creator_curreny'];
    }

    public function payoutRate($request)
    {


        $input['buyer_payment_currency']    = $request->input('buyer_payment_currency');
        $input['creator_receiving_currency']    = $request->input('creator_receiving_currency');
        $input['amount_paid_in_buyer_currency']    = $request->input('amount_paid_in_buyer_currency');

        $rate= FlutterRate::where('from',$input['creator_receiving_currency'] )
        ->where('to',$input['buyer_payment_currency'])
        ->first();


        //firstmarkdown on the amount paid in buyer currency
        $markDownPercentage = 0.05;
        $markDown = $markDownPercentage * $input['amount_paid_in_buyer_currency'];
        $amountPaid = $input['amount_paid_in_buyer_currency'] - $markDown;


        $flwRate = $rate->flw_rate;
        $newMarkDown = $markDownPercentage * $flwRate;
        $newRate = $flwRate - $newMarkDown;   

        //dd($markDown,$flwRate, $newRate);

        return $newRate * $amountPaid;
    }


}
