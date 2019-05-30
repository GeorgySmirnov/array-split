<?php

namespace tests\unit\models;

use app\models\ArraySplit;

class ArraySplitTest extends \Codeception\Test\Unit
{
    public function testCanSplitArray()
    {
        $this->assertEquals(
            4,
            ArraySplit::getSplitIndex(5, [5, 5, 1, 7, 2, 3, 5]));
        $this->assertEquals(
            3,
            ArraySplit::getSplitIndex(1, [1, 1, 1, 0, 0, 0]));
        $this->assertEquals(
            3,
            ArraySplit::getSplitIndex(1, [0, 0, 0, 1, 1, 1]));
        $this->assertEquals(
            3,
            ArraySplit::getSplitIndex(1, [1, 0, 1, 0, 1, 0]));
        $this->assertEquals(
            5,
            ArraySplit::getSplitIndex(1, [1, 1, 0, 0, 0, 0, 0]));
        $this->assertEquals(
            2,
            ArraySplit::getSplitIndex(1, [1, 1, 1, 1, 1, 0, 0]));
    }

    public function testCanDetermineUnsplitableArrays()
    {
        $this->assertEquals(
            -1,
            ArraySplit::getSplitIndex(1, [1, 1, 1, 1, 1, 1, 1]));
        $this->assertEquals(
            -1,
            ArraySplit::getSplitIndex(1, [0, 0, 0, 0, 0, 0, 0]));
        $this->assertEquals(
            -1,
            ArraySplit::getSplitIndex(1, [1]));
        $this->assertEquals(
            -1,
            ArraySplit::getSplitIndex(1, [0]));
        $this->assertEquals(
            -1,
            ArraySplit::getSplitIndex(1, []));
    }
}
