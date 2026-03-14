<?php

namespace App\Domains\Finance\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Finance\Actions\GetBankListAction;
use App\Domains\Finance\Actions\ValidateAccountAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentGatewayController extends Controller
{
    public function getBankList(GetBankListAction $action)
    {
        return response()->json([
            'status' => 'success',
            'data' => $action->execute(),
        ]);
    }

    public function validateBankAccount(Request $request, ValidateAccountAction $action)
    {
        $request->validate([
            'bank_code' => ['required', 'string'],
            'account_number' => ['required', 'string']
        ]);

        $result = $action->execute(
            $request->bank_code, 
            $request->account_number
        );

        return response()->json($result);
    }
}
