<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;
use Carbon\Carbon;

class TutorRegisterAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $repository, protected FileStorageInterface $storage)
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

        $data['curriculum_vitae_temp_path'] = $this->storage->uploadToTemp($data['curriculum_vitae']);
        $data['cv_name'] = basename($data['curriculum_vitae_temp_path']);
        $data['id_card_temp_path'] = $this->storage->uploadToTemp($data['id_card']); 
        $data['id_card_name'] = basename($data['id_card_temp_path']);
        $data['certificate_temp_path'] = $this->storage->uploadToTemp($data['certificate']);
        $data['certificate_name'] = basename($data['certificate_temp_path']);

        $user = $this->repository->createTutorData($data);
        $token = $this->repository->getToken($user->id);

        return $token;
    }
}
