<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Domains\Student\Ports\StudentServicePort;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateStudentBiodataAction
{
    public function __construct(protected StudentRepositoryInterface $repository, protected StudentServicePort $service) {}

    public function execute(int $userId, array $data)
    {
        $userData = [
            'name' => $data['name'],
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'telephone_number' => $data['telephone_number'],
            'province' => $data['province'],
            'regency' => $data['regency'],
            'district' => $data['district'],
            'subdistrict' => $data['subdistrict'],
        ];

        $studentData = [
            'class_id' => $data['class_id'],
        ];

        DB::beginTransaction();
        try {
            $this->service->updateUser($userId, $userData);
            $this->repository->update($userId, $studentData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal memperbarui biodata siswa: ' . $e->getMessage());
        }
    }
}