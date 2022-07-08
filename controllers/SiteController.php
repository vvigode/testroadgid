<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Links;

class SiteController extends Controller {
    
    public function actionIndex() {
        $model = new Links();
        $data = Yii::$app->request->post();
        if(Yii::$app->request->isAjax){
            $create_short = $model->short($data['Links']['shorted_link']);
            return $create_short;
        }
        if($header = ltrim(Yii::$app->request->url, '/')) {
            if($findUrl = $model::find()->where(['short_code' => $header])->one()){
                $redirectUrl = $findUrl->shorted_link;
                Yii::$app->getResponse()->redirect($redirectUrl);
                $model->checkIsBot($header);
            } else {
                return $this->render('index', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}
