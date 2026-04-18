<?php

namespace App\Domains\Penalty\Actions;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use Carbon\Carbon;

class GetUserWarningAction
{
    public function __construct(protected PenaltyRepositoryInterface $repository) {}

    public function execute($userId)
    {
        $warning = $this->repository->getUserWarning($userId);
        $warningOwned = $warning % 3;
        $sanction = $this->repository->getUserSanction($userId);

        if ($warning != 0){
            if ($sanction != null && Carbon::now()->lessThan($sanction)) {
                $displayWarning = 3;
            } else {
                $displayWarning = $warningOwned;
            }
        } else {
            $displayWarning = 0;
        }
        
        return $displayWarning;
    }
}