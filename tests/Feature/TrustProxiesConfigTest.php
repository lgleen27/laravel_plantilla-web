<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use Illuminate\Http\Middleware\TrustProxies;
use Tests\TestCase;

class TrustProxiesConfigTest extends TestCase
{
    protected function tearDown(): void
    {
        TrustProxies::flushState();
        parent::tearDown();
    }

    public function test_trust_proxies_accepts_boolean_true_and_trusts_forwarded_headers(): void
    {
        TrustProxies::flushState();
        config(['app.trust_proxies' => true]);

        (new AppServiceProvider($this->app))->boot();

        $response = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-For' => '203.0.113.10',
            'X-Forwarded-Host' => 'congela.frexal.net',
        ])->get('http://localhost/up');

        $response->assertStatus(200);
        $this->assertTrue(request()->isSecure());
        $this->assertSame('203.0.113.10', request()->ip());
        $this->assertSame('https://congela.frexal.net', request()->getSchemeAndHttpHost());
    }

    public function test_trust_proxies_accepts_case_insensitive_true_string(): void
    {
        TrustProxies::flushState();
        config(['app.trust_proxies' => 'True']);

        (new AppServiceProvider($this->app))->boot();

        $response = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-For' => '203.0.113.15',
        ])->get('http://localhost/up');

        $response->assertStatus(200);
        $this->assertTrue(request()->isSecure());
        $this->assertSame('203.0.113.15', request()->ip());
    }

    public function test_trust_proxies_accepts_comma_separated_ips(): void
    {
        TrustProxies::flushState();
        config(['app.trust_proxies' => '172.18.0.1, 10.0.0.1']);

        (new AppServiceProvider($this->app))->boot();

        $response = $this->withServerVariables(['REMOTE_ADDR' => '172.18.0.1'])
            ->withHeaders([
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-For' => '203.0.113.25',
            ])->get('http://localhost/up');

        $response->assertStatus(200);
        $this->assertTrue(request()->isSecure());
        $this->assertSame('203.0.113.25', request()->ip());
    }

    public function test_trust_proxies_ignores_forwarded_headers_when_disabled(): void
    {
        TrustProxies::flushState();
        config(['app.trust_proxies' => false]);

        (new AppServiceProvider($this->app))->boot();

        $response = $this->withServerVariables(['REMOTE_ADDR' => '172.18.0.1'])
            ->withHeaders([
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-For' => '203.0.113.99',
            ])->get('http://localhost/up');

        $response->assertStatus(200);
        $this->assertFalse(request()->isSecure());
        $this->assertSame('172.18.0.1', request()->ip());
    }
}
