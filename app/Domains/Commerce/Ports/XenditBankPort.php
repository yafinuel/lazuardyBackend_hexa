<?php

namespace App\Domains\Commerce\Ports;

interface XenditBankPort
{
    public function getAvailableBanks(): array;
    public function validateAccount(string $bankCode, string $accountNumber): array;
    public function createInvoice(string $externalId, int $amount, string $description, string $studentName, string $studentEmail, int $expiryDuration = 86400, ?string $successRedirectUrl = null, ?string $failureRedirectUrl = null): array;
}
