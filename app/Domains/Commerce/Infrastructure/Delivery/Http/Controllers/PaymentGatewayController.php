<?php

namespace App\Domains\Commerce\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Commerce\Actions\CheckoutPackageAction;
use App\Domains\Commerce\Actions\GetBankListAction;
use App\Domains\Commerce\Actions\ValidateAccountAction;
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

    public function orderPackage(Request $request, CheckoutPackageAction $action)
    {
        $data = $request->validate([
            'packages' => ['required', 'array'],
            'packages.*.id' => ['required', 'integer'],
            'packages.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $userId = $request->user()->id; // Ambil user ID dari token yang sudah di-authenticate
        $result = $action->execute(
            $userId,
            $request->all()
        );

        // Placeholder untuk endpoint checkout paket
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
}
