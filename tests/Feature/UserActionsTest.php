<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

test('Update user avatar', function () {
    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($user)->putJson('/user/avatar', [
        'avatar' => $image,
    ]);

    $response->assertStatus(200);

    $avatarUrl = json_decode($response->getContent())->avatar;
    $avatarFileName = "avatars/".pathinfo($avatarUrl, PATHINFO_BASENAME);

    $this->assertTrue(\Storage::disk('public')->exists($avatarFileName));
    $this->assertTrue(\Storage::disk('public')->delete($avatarFileName));
    $this->assertFalse(\Storage::disk('public')->exists($avatarFileName));
});
