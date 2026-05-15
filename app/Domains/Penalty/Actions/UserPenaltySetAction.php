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
        $this->repository->addWarning($userId);
        
        $user = $this->service->getUserById($userId);
        $warning = $user->warning;
        $sanction = $user->sanction;

        Log::info('UserPenaltySetAction: called', ['user_id' => $userId, 'warning' => $warning, 'sanction' => $sanction]);


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

        $newWarning = $this->repository->getUserWarning($userId);
        $newSanction = $this->repository->getUserSanction($userId);
        Log::info('UserPenaltySetAction: after addWarning', ['user_id' => $userId, 'new_warning' => $newWarning, 'new_sanction' => $newSanction]);
    }
}