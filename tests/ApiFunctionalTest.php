<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class ApiFunctionalTest extends KernelTestCase
{
    use HasBrowser;
    use ResetDatabase;

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

        $this->browser()
            ->get('/api/me', [
                'headers' => [
                    'Authorization' => 'Bearer '.$data['token']
                ]
            ])
            ->assertSuccessful()
            ->assertJson()
            ->assertJsonMatches('email', 'wouter@example.com')
        ;
    }
}
