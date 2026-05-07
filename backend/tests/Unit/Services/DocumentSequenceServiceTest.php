<?php

namespace Tests\Unit\Services;

use App\Services\DocumentSequenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentSequenceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_next_returns_gap_free_sequential_numbers_per_prefix(): void
    {
        $svc = app(DocumentSequenceService::class);

        $this->assertSame('INV-00001', $svc->next('INV'));
        $this->assertSame('INV-00002', $svc->next('INV'));
        $this->assertSame('INV-00003', $svc->next('INV'));
    }

    public function test_different_prefixes_have_independent_counters(): void
    {
        $svc = app(DocumentSequenceService::class);

        $this->assertSame('INV-00001', $svc->next('INV'));
        $this->assertSame('PUR-00001', $svc->next('PUR'));
        $this->assertSame('JV-00001',  $svc->next('JV'));
        $this->assertSame('INV-00002', $svc->next('INV'));
    }

    public function test_peek_does_not_advance_the_counter(): void
    {
        $svc = app(DocumentSequenceService::class);

        $svc->next('INV');                                  // → INV-00001
        $this->assertSame('INV-00002', $svc->peek('INV'));
        $this->assertSame('INV-00002', $svc->peek('INV'));  // peek again, still 00002
        $this->assertSame('INV-00002', $svc->next('INV'));  // actual issuance
    }
}
