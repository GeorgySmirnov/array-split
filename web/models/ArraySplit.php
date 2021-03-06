<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\InvalidArgumentException;

use app\models\User;

class ArraySplit extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%array_split}}';
    }

    public function rules()
    {
        return [
            [['number_n', 'split_index'], 'integer'],
            ['array', 'each', 'rule' => ['integer']],
        ];
    }

    public static function createSplit(User $user, int $N, array $array): ArraySplit
    {
        $split = new ArraySplit();
        
        $split->user_id = $user->id;
        $split->number_n = $N;
        $split->array = $array;
        if (!$split->validate(['number_n', 'array'])) {
            throw new InvalidArgumentException;
        }
        $split->split_index = self::getSplitIndex($N, $array);

        return $split;
    }
    
    /*
     * Разделяет массив array на две части таким образом,
     * чтобы количество чисел N в первой части
     * оказалось равно количеству чисел не равных N во второй
     * и это количество было больше 0.
     * Возвращает индекс элемента следующего за разделителем.
     * Если невозможно возвращаем -1.
     *
     * Используемый алгоритм разделяет массив постепенно добавляя элементы в левую и правую части
     * начиная с крайних элементов.
     *
     * В начале каждой итерации ожидается, что количество элементов N в левой части
     * и не N в правой равны (это условие выполняется на первой итерации, так-как
     * левая и правая части пусты).
     * Итерация состоит из следующих шагов:
     * 1. Если крайний левый элемент нераспределённого массива не равен N
     *   - он помещается в левую часть. Операция повторяется пока выполняется условие.
     * 2. Аналогично: в правую часть помещаются нераспределённые правые элементы равные N.
     *  * Предыдущие два шага не влияют на количество элементов N в левой и не N в правой частях.
     * 3. Если все элементы распределены, возвращаем индекс крайнего левого элемента правой части,
     *   если правая и левая части не пусты, либо -1, в противном случае.
     * 4. Если остались нераспределённые элементы, крайний левый будет N, правый - не N.
     *   Помещаем их в левую и правую часть соответственно, увеличивая количество N/не-N
     *   элементов в них на 1, и переходим к следующей итерации.
     */ 
    public static function getSplitIndex(int $N, array $array): int
    {
        // Крайний правый элемент левой части
        $left = -1;
        // Крайний левый элемент правой
        $right = count($array);
        
        while (true) {
            // Помещаем элементы в левую часть меняя индекс крайнего элемента
            while ($left + 1 < $right && $array[$left + 1] != $N) {
                $left++;
            }

            // Аналогично
            while ($left + 1 < $right && $array[$right - 1] == $N) {
                $right--;
            }

            // Если все элементы распределены
            if ($left + 1 == $right) {
                if ($right > 0 && $right < count($array)) {
                    return $right;
                } else {
                    return -1;
                }
            }

            // Помещаем N в левую часть и не N в правую
            $left++;
            $right--;
        }
    }
}
