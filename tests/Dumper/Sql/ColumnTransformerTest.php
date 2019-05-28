<?php
declare(strict_types=1);

namespace Smile\Anonymizer\Tests\Dumper\Sql;

use Smile\Anonymizer\Dumper\Sql\ColumnTransformer;
use Smile\Anonymizer\Tests\Converter\Dummy;
use Smile\Anonymizer\Tests\DbTestCase;

class ColumnTransformerTest extends DbTestCase
{
    /**
     * Check if a value is transformed properly.
     */
    public function testTransformValue()
    {
        $transformer = $this->createTransformer();

        $value = $transformer->transform('mytable', 'mycolumn', 'myvalue', []);
        $this->assertSame('test_myvalue', $value);
    }

    /**
     * Test if null values are ignored.
     */
    public function testNullValueNotTransformed()
    {
        $transformer = $this->createTransformer();

        $value = $transformer->transform('mytable', 'mycolumn', null, []);
        $this->assertNull($value);
    }

    /**
     * Test if unknown tables are ignored.
     */
    public function testIgnoredTables()
    {
        $transformer = $this->createTransformer();

        $value = $transformer->transform('mytable', 'unknown_column', 'myvalue', []);
        $this->assertSame('myvalue', $value);
    }

    /**
     * Test if unknown columns are ignored.
     */
    public function testIgnoredColumns()
    {
        $transformer = $this->createTransformer();

        $value = $transformer->transform('unknown_table', 'mycolumn', 'myvalue', []);
        $this->assertSame('myvalue', $value);
    }

    /**
     * Create a column transformer object.
     *
     * @return ColumnTransformer
     */
    private function createTransformer(): ColumnTransformer
    {
        $converters = [
            'mytable' => [
                'mycolumn' => new Dummy(),
            ],
        ];

        return new ColumnTransformer($converters);
    }
}