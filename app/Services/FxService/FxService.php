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

        //FlwRate
        $rate = $this->getFlutterRate($input['buyer_currency'],$input['creator_currency']);

        //markupPercentage
        $markupPercentage = config("constants.percentage.rate");

        //calculate new rate
        $newRate = $this->calculateNewRate($rate->flw_rate,$markupPercentage);

        return $newRate * $input['creator_selling_price_in_creator_curreny'];
    }

    public function payoutRate($request)
    {


        $input['buyer_payment_currency']    = $request->input('buyer_payment_currency');
        $input['creator_receiving_currency']    = $request->input('creator_receiving_currency');
        $input['amount_paid_in_buyer_currency']    = $request->input('amount_paid_in_buyer_currency');

        //flWrate
        $rate = $this->getFlutterRate($input['creator_receiving_currency'],$input['buyer_payment_currency']);

        //markdownPercentage
        $markDownPercentage = config("constants.percentage.rate");

          //firstmarkdown on the amount paid in buyer currency
        $amountPaid = $this->calculateAmountPaid($input['amount_paid_in_buyer_currency'],$markDownPercentage);   

        //calculate new payout
        $newRate = $this->calculateNewPayoutRate($rate->flw_rate,$markDownPercentage);

        return $newRate * $amountPaid;
    }


    protected function getFlutterRate($from,$to)
    {
        return FlutterRate::where('from', $from)->where('to', $to)->firstOrFail();
    }

    protected function calculateNewRate($flwRate,$percentage)
    {
        $markup = $flwRate * $percentage;
        return $flwRate + $markup;
    }

    protected function calculateNewPayoutRate($flwRate,$percentage)
    {
        $markdown = $flwRate * $percentage;
        return $flwRate - $markdown;
    }

    protected function calculateAmountPaid($amount,$percentage)
    {
        $markdown = $percentage * $amount;
        return $amount - $markdown;
    }


}
