<?php

namespace Tests\Unit\Schedule\Actions;

use App\Domains\Schedule\Actions\CancelScheduleAction;
use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Infrastructure\Services\ScheduleServiceAdapter;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Domains\User\Entities\UserEntity;
use App\Shared\Enums\RoleEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;
use Tests\TestCase;

class CancelScheduleActionTest extends TestCase
{
    private CancelScheduleAction $action;
    private ScheduleRepositoryInterface|MockInterface $scheduleRepository;
    private ScheduleServiceAdapter|MockInterface $scheduleService;
    private StudentRepositoryInterface|MockInterface $studentRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scheduleRepository = $this->mock(ScheduleRepositoryInterface::class);
        $this->scheduleService = $this->mock(ScheduleServiceAdapter::class);
        $this->studentRepository = $this->mock(StudentRepositoryInterface::class);

        $this->action = new CancelScheduleAction(
            $this->scheduleRepository,
            $this->scheduleService,
            $this->studentRepository
        );
    }

    /** @test */
    public function tutor_cancel_within_12_hours_gets_penalty()
    {
        // Arrange
        $userId = 1;
        $scheduleId = 1;
        $tutorUser = $this->createMockUser($userId, RoleEnum::TUTOR);
        $schedule = $this->createMockSchedule(Carbon::now()->addMinutes(60)); // 60 menit dari sekarang

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($tutorUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->scheduleService->shouldReceive('userPenaltySet')
            ->with($userId)
            ->once();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function tutor_cancel_beyond_12_hours_no_penalty()
    {
        // Arrange
        $userId = 1;
        $scheduleId = 1;
        $tutorUser = $this->createMockUser($userId, RoleEnum::TUTOR);
        $schedule = $this->createMockSchedule(Carbon::now()->addHours(24)); // 24 jam dari sekarang

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($tutorUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->scheduleService->shouldReceive('userPenaltySet')->never();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function student_cancel_within_12_hours_session_hangus()
    {
        // Arrange
        $userId = 2;
        $scheduleId = 2;
        $studentUser = $this->createMockUser($userId, RoleEnum::STUDENT);
        $schedule = $this->createMockSchedule(Carbon::now()->addMinutes(120)); // 120 menit dari sekarang
        $student = $this->createMockStudent($userId, session: 5);

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($studentUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->studentRepository->shouldReceive('getStudentById')
            ->with($userId)
            ->once()
            ->andReturn($student);

        // Session should NOT be incremented
        $this->studentRepository->shouldReceive('updateStudent')->never();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function student_cancel_beyond_12_hours_session_returned()
    {
        // Arrange
        $userId = 2;
        $scheduleId = 2;
        $studentUser = $this->createMockUser($userId, RoleEnum::STUDENT);
        $schedule = $this->createMockSchedule(Carbon::now()->addHours(24)); // 24 jam dari sekarang
        $student = $this->createMockStudent($userId, session: 5);

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($studentUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->studentRepository->shouldReceive('getStudentById')
            ->with($userId)
            ->once()
            ->andReturn($student);

        // Session should be incremented (string session '5' + 1 = int 6)
        $this->studentRepository->shouldReceive('updateStudent')
            ->with((int)$student->id, ['session' => ((int)$student->session) + 1])
            ->once();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function cancel_after_schedule_starts_no_action()
    {
        // Arrange
        $userId = 1;
        $scheduleId = 1;
        $tutorUser = $this->createMockUser($userId, RoleEnum::TUTOR);
        $schedule = $this->createMockSchedule(Carbon::now()->subMinutes(30)); // 30 menit yang lalu

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($tutorUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->scheduleService->shouldReceive('userPenaltySet')->never();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function tutor_cancel_just_before_12_hours_gets_penalty()
    {
        // Arrange - 11:59 hours before schedule
        $userId = 1;
        $scheduleId = 1;
        $tutorUser = $this->createMockUser($userId, RoleEnum::TUTOR);
        // 719 minutes = 11 hours 59 minutes (still within 12 hour window)
        $schedule = $this->createMockSchedule(Carbon::now()->addMinutes(719));

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($tutorUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->scheduleService->shouldReceive('userPenaltySet')
            ->with($userId)
            ->once();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function tutor_cancel_at_or_beyond_12_hours_no_penalty()
    {
        // Arrange - exactly or more than 12 hours before schedule
        $userId = 1;
        $scheduleId = 1;
        $tutorUser = $this->createMockUser($userId, RoleEnum::TUTOR);
        // 721 minutes = 12 hours 1 minute (beyond 12 hour window)
        $schedule = $this->createMockSchedule(Carbon::now()->addMinutes(721));

        $this->scheduleService->shouldReceive('getUserById')
            ->with($userId)
            ->once()
            ->andReturn($tutorUser);

        $this->scheduleRepository->shouldReceive('getScheduleById')
            ->with($scheduleId)
            ->once()
            ->andReturn($schedule);

        $this->scheduleService->shouldReceive('userPenaltySet')->never();

        $this->scheduleRepository->shouldReceive('cancelSchedule')
            ->with($scheduleId)
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once();

        // Act
        $result = $this->action->execute($userId, $scheduleId);

        // Assert
        $this->assertTrue($result);
    }

    // Helper methods
    private function createMockUser(int $id, RoleEnum $role): UserEntity
    {
        return new UserEntity(
            id: (string) $id,
            name: "Test User",
            email: "test@example.com",
            emailVerifiedAt: null,
            password: "hashed",
            telephoneNumber: "08123456789",
            telephoneVerifiedAt: null,
            profilePhotoUrl: null,
            dateOfBirth: null,
            gender: null,
            religion: null,
            homeAddress: null,
            role: $role,
            warning: 0,
            sanction: null,
            latitude: null,
            longitude: null,
        );
    }

    private function createMockSchedule(Carbon $startAt): ScheduleEntity
    {
        return new ScheduleEntity(
            id: 1,
            tutorId: 1,
            studentId: 2,
            subjectId: 1,
            date: $startAt->copy()->startOfDay(),
            startTime: $startAt->copy(),
            endTime: $startAt->copy()->addHours(1),
            status: "scheduled",
            learningMethod: "online",
            meetingLink: "https://meet.example.com/123",
            address: null,
            tutorName: "John Doe",
            subjectName: "Math",
            studentName: "Jane Doe",
            tutorTelephoneNumber: "08123456789",
            studentTelephoneNumber: "08987654321",
        );
    }

    private function createMockStudent(int $userId, int $session = 5): StudentEntity
    {
        return new StudentEntity(
            id: (string) $userId,
            name: "Test Student",
            email: "student@example.com",
            emailVerifiedAt: null,
            telephoneNumber: "08123456789",
            telephoneVerifiedAt: null,
            profilePhotoUrl: null,
            dateOfBirth: null,
            gender: null,
            religion: null,
            homeAddress: null,
            role: "student",
            warning: 0,
            sanction: null,
            latitude: null,
            longitude: null,
            session: (string) $session,
            classId: 1,
        );
    }
}
