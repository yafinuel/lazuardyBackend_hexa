<?php

namespace App\Domains\Commerce\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Commerce\Actions\CallbackFailedHandlePayoutAction;
use App\Domains\Commerce\Actions\CallbackSuccessHandlePayoutAction;
use App\Domains\Commerce\Actions\CheckoutPackageAction;
use App\Domains\Commerce\Actions\GetBankListAction;
use App\Domains\Commerce\Actions\GetPayoutHistoryAction;
use App\Domains\Commerce\Actions\ProcessApprovedPayoutAction;
use App\Domains\Commerce\Actions\ProcessPaymentExpiredAction;
use App\Domains\Commerce\Actions\ProcessPaymentSuccessAction;
use App\Domains\Commerce\Actions\ProcessRejectedPayoutAction;
use App\Domains\Commerce\Actions\RequestPayoutAction;
use App\Domains\Commerce\Actions\ValidateAccountAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function handlePaymentCallback(Request $request, ProcessPaymentSuccessAction $successAction, ProcessPaymentExpiredAction $expiredAction)
    {
        $data = $request->all();

        switch ($data['status']) {
            case 'SETTLED':
            case 'PAID':
                $successAction->execute($data);
                break;
            case 'EXPIRED':
                $expiredAction->execute($data);
                break;
            default:
                Log::info("Callback received for invoice ". $data['external_id'] . "with status: " . $data['status']);
                break;
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment callback received'
        ]);
    }

    public function payoutRequest(Request $request, RequestPayoutAction $action)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000']
        ]);

        $userId = $request->user()->id;

        $action->execute($userId, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Payout request submitted successfully'
        ]);
    }

    public function payoutApprovalFromAdmin(Request $request, ProcessApprovedPayoutAction $approvedAction, ProcessRejectedPayoutAction $rejectedAction)
    {
        $data = $request->validate([
            'payout_id' => ['required', 'integer'],
            'approved' => ['required', 'boolean'],
            'rejection_reason' => ['nullable', 'string']
        ]);

        $adminId = $request->user()->id;

        if($data['approved']){
            $approvedAction->execute($adminId, $data['payout_id']);
        } else {
            $rejectedAction->execute($adminId, $data['payout_id'], $data['rejection_reason'] ?? 'No reason provided');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payout processed successfully'
        ]);
    }


    public function handlePayoutCallback(
        Request $request, 
        CallbackSuccessHandlePayoutAction $successAction, 
        CallbackFailedHandlePayoutAction $failedAction
    ) {
        $payload = $request->all();

        $payoutNumber = null;
        $xenditStatus = null;
        $cleanData = [];

        // 1. Deteksi ini adalah xendit V3 atau V2
        if (isset($payload['data'])) {
            $cleanData    = $payload['data'];
            $payoutNumber = $payload['data']['reference_id'] ?? null;
            $xenditStatus = $payload['data']['status'] ?? null;
        } 
        else {
            $cleanData    = $payload;
            $payoutNumber = $payload['external_id'] ?? null;
            $xenditStatus = $payload['status'] ?? null;
        }

        Log::info("Payout callback processed. Number: " . $payoutNumber . " | Status: " . $xenditStatus);

        if (!$payoutNumber) {
            return response()->json(['message' => 'Format payload tidak dikenali'], 400);
        }

        $cleanData['reference_id'] = $payoutNumber;

        if (in_array($xenditStatus, ['SUCCESS', 'SUCCEEDED'])) {
            $successAction->execute($cleanData);
        } 
        elseif ($xenditStatus === 'FAILED') {
            $failedAction->execute($cleanData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback processed successfully'
        ]);
    }

    public function getPayoutHistory(Request $request, GetPayoutHistoryAction $action)
    {
        $userId = $request->user()->id;

        $payouts = $action->execute($userId);

        return response()->json([
            'status' => 'success',
            'data' => $payouts
        ]);
    }
}
