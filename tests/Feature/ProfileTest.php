<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use LazilyRefreshDatabase;

    private ?string $profilesBackupPath = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Los tests escriben en public_path('profiles') real; respaldamos su contenido
        // para no perder imágenes de perfil ya subidas por un usuario real.
        $profilesPath = public_path('profiles');

        if (File::isDirectory($profilesPath)) {
            $this->profilesBackupPath = storage_path('framework/testing/profiles-backup-'.uniqid());
            File::copyDirectory($profilesPath, $this->profilesBackupPath);
        }
    }

    protected function tearDown(): void
    {
        $profilesPath = public_path('profiles');
        File::deleteDirectory($profilesPath);

        if ($this->profilesBackupPath) {
            File::copyDirectory($this->profilesBackupPath, $profilesPath);
            File::deleteDirectory($this->profilesBackupPath);
        }

        parent::tearDown();
    }

    public function test_authenticated_user_can_upload_a_profile_image(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('profile.image.update'), [
            'file' => UploadedFile::fake()->image('avatar.png'),
        ]);

        $response->assertOk()->assertJsonStructure(['image', 'url']);
        $this->assertNotNull($user->fresh()->profile_image);
        $this->assertFileExists(public_path('profiles/'.$user->fresh()->profile_image));
    }

    public function test_replacing_profile_image_deletes_the_old_file(): void
    {
        $user = User::factory()->create(['profile_image' => 'old.webp']);
        File::ensureDirectoryExists(public_path('profiles'));
        File::put(public_path('profiles/old.webp'), 'old image');

        $response = $this->actingAs($user)->postJson(route('profile.image.update'), [
            'file' => UploadedFile::fake()->image('new.png'),
        ]);

        $response->assertOk();
        $newImage = $user->fresh()->profile_image;
        $this->assertNotSame('old.webp', $newImage);
        $this->assertFileDoesNotExist(public_path('profiles/old.webp'));
        $this->assertFileExists(public_path('profiles/'.$newImage));
    }

    public function test_guest_cannot_upload_a_profile_image(): void
    {
        $response = $this->post(route('profile.image.update'));

        $response->assertRedirectToRoute('login');
    }
}
