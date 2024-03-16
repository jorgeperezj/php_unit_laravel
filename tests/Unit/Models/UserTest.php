<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{
    public function test_has_many_repositories(): void
    {
        $user = new User;
        $this->assertInstanceOf(Collection::class, $user->repositories);
    }
}
