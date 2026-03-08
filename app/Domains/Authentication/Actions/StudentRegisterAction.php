<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use Carbon\Carbon;

class StudentRegisterAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {}

    public function execute(array $data): string
    {
        $data['email_verified_at'] = Carbon::now();

        $homeAddress = [
            'province' => $data["province"],
            'regency' => $data["regency"],
            'district' => $data["district"],
            'subdistrict' => $data["subdistrict"],
        ];

        $data['home_address'] = $homeAddress;

        $user = $this->repository->createStudentData($data);
        $token = $this->repository->getToken($user->id);

        return $token;
    }
}
