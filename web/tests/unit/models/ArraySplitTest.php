<?php

namespace tests\unit\models;

use app\models\ArraySplit;
use app\models\User;

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

    public function testCanCreateRecords()
    {
        $split = new ArraySplit();

        $split->user_id = 1;
        $split->number_n = 1;
        $split->array = [1,1,1,0,0,0];
        $split->split_index = 3;

        $split->save();

        $new = ArraySplit::findOne(['user_id' => 1]);

        $this->assertEquals([1,1,1,0,0,0], $new->array);
        $this->assertEquals(1, $new->number_n);
    }

    public function testCanValidateFields()
    {
        $split = new ArraySplit();

        $split->number_n = 1;
        $this->assertTrue($split->validate(['number_n']));
        $split->number_n = -1;
        $this->assertTrue($split->validate(['number_n']));
        $split->number_n = 1000000000;
        $this->assertTrue($split->validate(['number_n']));

        $split->number_n = 'fds';
        $this->assertFalse($split->validate(['number_n']));

        $split->array = [1,2,3,4];
        $this->assertTrue($split->validate(['array']));
        $split->array = [1];
        $this->assertTrue($split->validate(['array']));
        $split->array = [];
        $this->assertTrue($split->validate(['array']));
        
        $split->array = '1234';
        $this->assertFalse($split->validate(['array']));
        $split->array = [1,'two',3,4];
        $this->assertFalse($split->validate(['array']));
        $split->array = [1,[2,3],4,5];
        $this->assertFalse($split->validate(['array']));
    }

    public function testCanCreateNewSplit()
    {
        $user = User::findOne(['access_token' => '00000000000000000000000000000000']);
        $split = ArraySplit::createSplit($user, 1, [1,1,1,0,0,0]);

        $this->assertInstanceOf(ArraySplit::class, $split);
        $this->assertEquals($user->id, $split->user_id);
        $this->assertEquals(3, $split->split_index);
        $this->assertTrue($split->save());
    }
}
