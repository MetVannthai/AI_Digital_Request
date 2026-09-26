<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/profile')->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create(['username' => 'original-user']);

        $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'username' => 'test.user',
            'department' => 'Finance',
            'phone' => '555-0100',
        ])->assertSessionHasNoErrors()->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('Test User', $user->name);
        $this->assertSame('test.user', $user->username);
        $this->assertSame('Finance', $user->department);
    }
}
