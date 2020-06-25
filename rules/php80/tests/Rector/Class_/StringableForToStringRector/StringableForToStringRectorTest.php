<?php

declare(strict_types=1);

namespace Rector\Php80\Tests\Rector\Class_\StringableForToStringRector;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class StringableForToStringRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $file): void
    {
        $this->doTestFileInfo($file);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return StringableForToStringRector::class;
    }
}
