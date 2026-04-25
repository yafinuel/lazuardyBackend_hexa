<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Ports\UserRepositoryInterface;

class UpdateUserAction
{
    public function __construct(protected UserRepositoryInterface $repository) {}

    public function execute(int $userId, array $data): void
    {
        $province = $data['province'] ?? null;
        $regency = $data['regency'] ?? null;
        $district = $data['district'] ?? null;
        $subdistrict = $data['subdistrict'] ?? null;

        if ($province || $regency || $district || $subdistrict) {
            $data['home_address'] = [
                'province' => $province,
                'regency' => $regency,
                'district' => $district,
                'subdistrict' => $subdistrict,
            ];
        }

        unset($data['province'], $data['regency'], $data['district'], $data['subdistrict']);

        $this->repository->update($userId, $data);
    }
}