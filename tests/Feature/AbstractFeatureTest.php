<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

abstract class AbstractFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
    }

    public function authenticate(User $user = null)
    {
        if (!$user) {
            $user = User::factory()->create();
        }

        $this->user = $user;

        $this->actingAs($user);
    }
}
