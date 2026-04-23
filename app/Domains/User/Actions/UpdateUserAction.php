<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Ports\UserRepositoryInterface;

class UpdateUserAction
{
    public function __construct(protected UserRepositoryInterface $repository) {}

    public function execute(int $userId, array $data): void
    {
        if ($data['province'] || $data['regency'] || $data['district'] || $data['subdistrict']) {
            $data['home_address'] = [
                'province' => $data['province'],
                'regency' => $data['regency'],
                'district' => $data['district'],
                'subdistrict' => $data['subdistrict'],
            ];
        }

        $this->repository->update($userId, $data);
    }
}