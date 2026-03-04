<?php

namespace Tests\Unit\Unit\Authentication;

use App\Domains\Authentication\Actions\AuthenticateSocialAction;
use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthenticateSocialActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_it_creates_new_user_if_email_does_not_exist()
    {
        // 1. Persiapan Mocking (Pura-pura jadi Repository & Socialite User)
        $mockRepo = Mockery::mock(UserRepositoryInterface::class);
        $mockSocialUser = Mockery::mock('stdClass');
        
        $mockSocialUser->shouldReceive('getEmail')->andReturn('gemini@example.com');
        $mockSocialUser->shouldReceive('getName')->andReturn('Gemini AI');
        $mockSocialUser->shouldReceive('getId')->andReturn('google-123');

        // 2. Ekspektasi: Repository harus cari email, lalu panggil create karena tidak ketemu
        $mockRepo->shouldReceive('findByEmail')
            ->with('gemini@example.com')
            ->once()
            ->andReturn(null);
        
        $mockRepo->shouldReceive('create')
            ->once()
            ->andReturn(new User(['name' => 'Gemini AI', 'email' => 'gemini@example.com']));

        // 3. Jalankan Action
        $action = new AuthenticateSocialAction($mockRepo);
        $result = $action->execute($mockSocialUser, 'google');

        // 4. Assert (Pastikan hasilnya benar)
        $this->assertEquals('Gemini AI', $result->name);
    }

    public function test_it_returns_existing_user_if_email_exists()
{
    $mockRepo = Mockery::mock(UserRepositoryInterface::class);
    $mockSocialUser = Mockery::mock('stdClass');

    // 1. Buat instance user
    $existingUser = new User([
        'name' => 'User Lama', 
        'email' => 'lama@example.com'
    ]);
    
    // 2. PAKSA isi ID (karena Eloquent biasanya menjaga ID agar tidak mass-assignable)
    $existingUser->id = 1; 

    $mockSocialUser->shouldReceive('getEmail')->andReturn('lama@example.com');
    $mockSocialUser->shouldReceive('getId')->andReturn('google-123');

    $mockRepo->shouldReceive('findByEmail')
        ->once()
        ->andReturn($existingUser);
        
    // Pastikan Mock mengekspektasikan ID bernilai 1
    $mockRepo->shouldReceive('updateSocialId')
        ->with(1, 'google', 'google-123')
        ->once();

    $action = new AuthenticateSocialAction($mockRepo);
    $result = $action->execute($mockSocialUser, 'google');

    $this->assertEquals('User Lama', $result->name);
    $this->assertEquals(1, $result->id);
}
}
