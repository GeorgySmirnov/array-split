<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

use app\models\ArraySplit;
use app\models\User;

class SplitController extends Controller
{
    public $defaultAction = 'split';

    public $N;
    public $userId;

    public function options($actionID)
    {
        return [
            'N',
            'userId',
        ];
    }

    public function optionAliases()
    {
        return [
            'n' => 'N',
            'u' => 'userId',
        ];
    }
    
    public function actionSplit(array $array)
    {
        if (count(array_filter($array, function ($x) {
            return !is_numeric($x);
        })) > 0) {
            echo 'Bad array values!' . PHP_EOL;
            return ExitCode::DATAERR;
        }
        $array = array_map(function ($x) { return intval($x); }, $array);

        if (!is_numeric($this->N)) {
            echo 'Bad N value!' . PHP_EOL;
            return ExitCode::DATAERR;
        }
        
        if ($this->userId) {
            $user = User::findOne(['id' => $this->userId]);
            if (!$user) {
                echo 'Invalid user ID!' . PHP_EOL;
                return ExitCode::DATAERR;
            }
            $split = ArraySplit::createSplit($user, $this->N, $array);
            $split->save();
            echo $split->split_index . PHP_EOL;
        } else {
            echo ArraySplit::getSplitIndex($this->N, $array) . PHP_EOL;
        }

        return ExitCode::OK;
    }
}
