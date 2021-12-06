<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class ApiFunctionalTest extends KernelTestCase
{
    use HasBrowser;

    public function testApiProcess(): void
    {
        $browser = $this->browser()
            ->post('/api/login', [
                'json' => [
                    'email' => 'wouter@example.com',
                    'password' => 'symfony'
                ],
            ])
            ->assertSuccessful()
            ->assertJson()
        ;
        $data = json_decode($browser->response()->body(), true);
        dd($data);
    }
}
