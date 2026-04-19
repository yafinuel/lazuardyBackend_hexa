<?php

namespace App\Domains\Authentication\Infrastructure\Services;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Domains\FileManager\Actions\MoveToPermanentPathAction;
use App\Domains\FileManager\Actions\SaveJobApplicationLetterAction;
use App\Domains\OtpManager\Actions\SendOtpAction;
use App\Domains\OtpManager\Actions\VerifyOtpAction;
use App\Domains\Schedule\Actions\CreateTutorScheduleAction;
use App\Domains\Subject\Actions\CreateTutorSubjectAction;
use App\Domains\Tutor\Actions\CreateTutorAction;
use App\Domains\User\Actions\CreateUserAction;
use App\Domains\User\Actions\ResetPasswordAction;
use App\Models\User;
use App\Shared\Enums\FileTypeEnum;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthenticationServiceAdapter implements AuthenticationServicePort
{
    public function __construct(
        protected CreateUserAction $createUserAction,
        protected CreateTutorAction $createTutorAction,
        protected CreateTutorSubjectAction $createTutorSubjectAction,
        protected CreateTutorScheduleAction $createTutorScheduleAction,
        protected SaveJobApplicationLetterAction $saveJobApplicationLetterAction,
        protected MoveToPermanentPathAction $moveToPermanentPathAction,
        protected SendOtpAction $sendOtpAction,
        protected VerifyOtpAction $verifyOtpAction,
        protected ResetPasswordAction $resetPasswordAction,
    ){}

    public function tutorRegister(array $userData, array $tutorData, int $subjectId, array $scheduleData, array $fileData): int
    {
        DB::beginTransaction();
        try {
            $userId = $this->createUserAction->execute($userData);
            $tutorId = $this->createTutorAction->execute($userId, $tutorData);

            $this->createTutorSubjectAction->execute([
                'tutor_id' => $tutorId,
                'subject_id' => $subjectId,
            ]);
            $this->createTutorScheduleAction->execute($tutorId, $scheduleData);
            
            $idCard = $this->saveJobApplicationLetterAction->execute($userId, $fileData['id_card'], FileTypeEnum::ID_CARD);
            $cv = $this->saveJobApplicationLetterAction->execute($userId, $fileData['curriculum_vitae'], FileTypeEnum::CV);
            $certificate = $this->saveJobApplicationLetterAction->execute($userId, $fileData['certificate'], FileTypeEnum::CERTIFICATE);

            DB::commit();
            DB::afterCommit(
                function () use ($idCard, $cv, $certificate, $userId) {
                    $this->moveToPermanentPathAction->execute($idCard['id'], $idCard['temp_path'], FileTypeEnum::ID_CARD->value . '/' . $userId);
                    $this->moveToPermanentPathAction->execute($cv['id'], $cv['temp_path'], FileTypeEnum::CV->value . '/' . $userId);
                    $this->moveToPermanentPathAction->execute($certificate['id'], $certificate['temp_path'], FileTypeEnum::CERTIFICATE->value . '/' . $userId);
                }
            );
            return $userId;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function studentRegister(array $userData, array $studentData): int
    {
        DB::beginTransaction();
        try {
            $userId = $this->createUserAction->execute($userData);
            $this->createTutorAction->execute($userId, $studentData);
            DB::commit();
            return $userId;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getToken(int $userId): string
    {
        User::find($userId)->tokens()->delete();
        $token = User::find($userId)->createToken('auth_token')->plainTextToken;
        return $token;
    }

    public function registerOtpEmail(string $email, OtpIdentifierEnum $identifierType, OtpVerificationTypeEnum $verificationType, string $subject, string $title): string
    {
        return $this->sendOtpAction->execute($email, $identifierType, $verificationType, $subject, $title);
    }

    public function verifyOtpEmail(string $code, string $identifier, OtpIdentifierEnum $identifierType, OtpVerificationTypeEnum $verificationType): ?string
    {
        return $this->verifyOtpAction->execute($code, $identifier, $identifierType, $verificationType);
    }

    public function resetPassword(string $email, string $resetToken, string $newPassword): void
    {
        $this->resetPasswordAction->execute($email, $resetToken, $newPassword);
    }
}