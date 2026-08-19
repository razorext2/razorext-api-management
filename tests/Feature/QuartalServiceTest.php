<?php

use App\Services\TechnicianPoint\QuartalService;
use Carbon\Carbon;

/**
 * Tests for QuartalService - pure logic (quartal date calculation).
 * DB-dependent tests (isAlreadyRedeemed) skipped due to test DB unavailability.
 */
beforeEach(function () {
    $this->withoutExceptionHandling();
    $this->service = new QuartalService;
});

describe('getQuartalRange', function () {
    it('returns correct range for Q1 (cross-year)', function () {
        $range = $this->service->getQuartalRange(1, 2026);

        expect($range['from']->toDateString())->toBe('2025-12-26')
            ->and($range['to']->toDateString())->toBe('2026-03-25');
    });

    it('returns correct range for Q2', function () {
        $range = $this->service->getQuartalRange(2, 2026);

        expect($range['from']->toDateString())->toBe('2026-03-26')
            ->and($range['to']->toDateString())->toBe('2026-06-25');
    });

    it('returns correct range for Q3', function () {
        $range = $this->service->getQuartalRange(3, 2026);

        expect($range['from']->toDateString())->toBe('2026-06-26')
            ->and($range['to']->toDateString())->toBe('2026-09-25');
    });

    it('returns correct range for Q4', function () {
        $range = $this->service->getQuartalRange(4, 2026);

        expect($range['from']->toDateString())->toBe('2026-09-26')
            ->and($range['to']->toDateString())->toBe('2026-12-25');
    });

    it('throws exception for invalid quarter', function () {
        $this->service->getQuartalRange(5, 2026);
    })->throws(InvalidArgumentException::class);
});

describe('getCurrentQuartal', function () {
    it('detects Q1 in January', function () {
        Carbon::setTestNow(Carbon::create(2026, 1, 15));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(1)
            ->and($current['year'])->toBe(2026);
    });

    it('detects Q1 in late December (cross-year start)', function () {
        Carbon::setTestNow(Carbon::create(2025, 12, 27));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(1)
            ->and($current['year'])->toBe(2026);
    });

    it('detects Q2 in April', function () {
        Carbon::setTestNow(Carbon::create(2026, 4, 10));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(2)
            ->and($current['year'])->toBe(2026);
    });

    it('detects Q3 in July', function () {
        Carbon::setTestNow(Carbon::create(2026, 7, 1));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(3)
            ->and($current['year'])->toBe(2026);
    });

    it('detects Q4 in October', function () {
        Carbon::setTestNow(Carbon::create(2026, 10, 15));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(4)
            ->and($current['year'])->toBe(2026);
    });

    it('detects boundary: March 25 is still Q1', function () {
        Carbon::setTestNow(Carbon::create(2026, 3, 25));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(1)
            ->and($current['year'])->toBe(2026);
    });

    it('detects boundary: March 26 is Q2', function () {
        Carbon::setTestNow(Carbon::create(2026, 3, 26));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(2)
            ->and($current['year'])->toBe(2026);
    });

    it('detects boundary: December 25 is still Q4', function () {
        Carbon::setTestNow(Carbon::create(2026, 12, 25));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(4)
            ->and($current['year'])->toBe(2026);
    });

    it('detects boundary: December 26 is Q1 of next year', function () {
        Carbon::setTestNow(Carbon::create(2026, 12, 26));

        $current = $this->service->getCurrentQuartal();

        expect($current['quarter'])->toBe(1)
            ->and($current['year'])->toBe(2027);
    });

    afterEach(function () {
        Carbon::setTestNow();
    });
});

describe('config customization', function () {
    it('reads quartal config correctly', function () {
        $quarters = config('quartal.quarters');

        expect($quarters)->toHaveCount(4)
            ->and($quarters[1]['cross_year'])->toBeTrue()
            ->and($quarters[2]['cross_year'])->toBeFalse();
    });
});
