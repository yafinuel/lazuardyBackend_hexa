<?php

namespace App\Domains\Penalty\Actions;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use App\Domains\Penalty\Ports\PenaltyServicePort;
use Illuminate\Support\Facades\Log;

class UserPenaltySetAction
{
    public function __construct(protected PenaltyRepositoryInterface $repository,protected PenaltyServicePort $service) {}

    public function execute(int $userId)
    {
        $user = $this->service->getUserById($userId);
        $warning = $user->warning;
        $sanction = $user->sanction;

        if ($warning >= 3){
            $warningCount = $warning % 3;
            if ($warningCount == 0){
                if($sanction){
                    $sanctionEndDate = $sanction->addDays(7);
                } else {
                    $sanctionEndDate = now()->addDays(7);
                }
                $this->repository->setSanction($userId, $sanctionEndDate);
            }
        }

        Log::info("User with ID {$userId} has reached warning count of {$warning}. Current sanction end date: {$sanction}. Current user warning {$warning}");
        $this->repository->addWarning($userId);
    }
}