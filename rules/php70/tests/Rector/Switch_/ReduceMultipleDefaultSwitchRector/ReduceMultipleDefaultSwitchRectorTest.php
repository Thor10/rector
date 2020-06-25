<?php

declare(strict_types=1);

namespace Rector\Php70\Tests\Rector\Switch_\ReduceMultipleDefaultSwitchRector;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Php70\Rector\Switch_\ReduceMultipleDefaultSwitchRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ReduceMultipleDefaultSwitchRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $file): void
    {
        $this->doTestFileInfoWithoutAutoload($file);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return ReduceMultipleDefaultSwitchRector::class;
    }
}
