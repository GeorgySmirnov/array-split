<?php

namespace app\models;

use yii\db\ActiveRecord;

class ArraySplit extends ActiveRecord
{
    public static function getSplitIndex(int $N, array $array): int
    {
        for ($i = 1; $i <= count($array) - 1; $i++) {
            $left = array_slice($array, 0, $i);
            $right = array_slice($array, $i);

            // count occurrences of N in $left
            $leftNs = count(array_filter($left, function($x) use ($N) {
                return $x == $N;
            }));
            // count occurrences of non N in $right
            $rightNonNs = count(array_filter($right, function($x) use ($N) {
                return $x != $N;
            }));

            if ($leftNs == $rightNonNs) {
                return $i;
            }
        }

        return -1;
    }
}
