<?php
declare(strict_types=1);

namespace Kkkonrad\FreeShippingBar\Test\Unit\ViewModel;

use Kkkonrad\FreeShippingBar\ViewModel\Progress;
use PHPUnit\Framework\TestCase;

class ProgressTest extends TestCase
{
    /**
     * @dataProvider cases
     */
    public function testProgress(float $subtotal, int $percent, float $remaining): void
    {
        $this->assertSame([$percent, $remaining], Progress::progress(50.0, $subtotal));
    }

    public static function cases(): array
    {
        return [
            'empty-ish' => [0.0, 0, 50.0],
            'half' => [25.0, 50, 25.0],
            // A full bar would read "done" while a grosz is still missing
            'one grosz short' => [49.99, 99, 0.01],
            'float noise does not leave 0.0000001 to go' => [0.1 + 0.2 + 49.7, 100, 0.0],
            'exactly there' => [50.0, 100, 0.0],
            'over' => [80.0, 100, 0.0],
        ];
    }
}
