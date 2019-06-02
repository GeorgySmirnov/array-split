<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use \yii\base\InvalidArgumentException;
use \yii\web\BadRequestHttpException;

use app\models\User;
use app\models\ArraySplit;

class SplitController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    QueryParamAuth::className(),
                ],
            ],
        ];
    }

    public function actionSplit()
    {
        $N = \Yii::$app->request->post('N');
        $array = \Yii::$app->request->post('array');
        
        if (!$N || !$array) {
            throw new BadRequestHttpException;
        }
        
        $user = \Yii::$app->user->identity;

        try {
            $split = ArraySplit::createSplit($user, $N, $array);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException;
        }

        $split->save();
        
        return $this->asJson([
            'N' => $split->number_n,
            'array' => $split->array,
            'split_index' => $split->split_index
        ]);
    }
}
