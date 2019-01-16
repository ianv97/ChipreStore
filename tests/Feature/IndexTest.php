<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    /* usuario_id */
    function test_index()
    {
        $this->get('/')
                ->assertStatus(200);
    }
}
