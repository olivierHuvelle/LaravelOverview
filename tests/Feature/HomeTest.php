<?php

namespace Tests\Feature;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_homePage_success()
    {
        $response = $this->get(route('home.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Homepage');
    }

    public function test_contactPage_success()
    {
        $response = $this->get(route('home.contact'));
        $response->assertStatus(200);
        $response->assertSeeText('Contact page');
    }
}
