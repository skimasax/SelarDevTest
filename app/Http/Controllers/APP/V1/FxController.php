<?php

namespace App\Http\Controllers\APP\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellContentRequest;
use App\Services\FxService\FxService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class FxController extends Controller
{
    use HttpResponses;
    protected $fxservice;

    public function __construct(FxService $FxService)
    {
        $this->fxservice = $FxService;
    }
    //
    public function getStoreRate(SellContentRequest $request)
    {
            $attributes = $request->validated();

            try {
               $data = $this->fxservice->storeRate($request);
               return $this->success($data,'price fetched successfully');
            } catch (\Throwable $err) {
                //throw $th;
                return $this->error("","error occured",500);
            }
    }

    public function getPayoutRate(SellContentRequest $request)
    {
            $attributes = $request->validated();

            try {
               $data = $this->fxservice->storeRate($request);
               return $this->success($data,'price fetched successfully');
            } catch (\Throwable $err) {
                //throw $th;
                return $this->error("","error occured",500);
            }
    }

    
}
