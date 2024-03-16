<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_upload(): void
    {
        Storage::fake('local');
        $response = $this->post('profile', [
            'photo' => $photo = UploadedFile::fake()->image('photo.png')
        ]);

        Storage::disk('local')->assertExists("profiles/{$photo->hashName()}");
        $response->assertRedirect('profile');
    }

    public function test_photo_required():void
    {
        $response = $this->post('profile', ['photo' => '']);
        $response->assertSessionHasErrors('photo');
    }
}
