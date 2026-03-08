<?php

namespace App\Http\Controllers\Finance;

use App\Domains\Finance\Actions\GetBankListAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function getBankList(GetBankListAction $action)
    {
        return response()->json([
            'status' => 'success',
            'data' => $action->execute(),
        ]);
    }

    public function validateBankAccount(Request $request, )
    {

    }
}
