<?php

namespace App\Tests;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class ApiFunctionalTest extends KernelTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testApiProcess(): void
    {
        UserFactory::createOne([
            'email' => 'wouter@example.com',
            'plainPassword' => 'symfony'
        ]);

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
        $data = $browser->json()->decoded();

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
