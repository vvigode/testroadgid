<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\httpclient\Client;

class Links extends ActiveRecord {
    
    public static function tableName()
    {
        return 'links';
    }
    
    public $link;
    
    public function rules() {
        return [
            [['shorted_link'], 'required', 'message'=>'Введите ссылку'],
            [['shorted_link'], 'url', 'message'=>'Недействительная ссылка'],
        ];
    }
    
    public function short($shorted_link) {
        if($isDouble = Links::find()->where(['shorted_link' => $shorted_link])->one()) {
            $short_code = $isDouble->short_code;
        } else {
            $short_code = $this->randomShortCode();
            $model = new Links();
            $model->month = date('Y')."-".date('m');
            $model->shorted_link = $shorted_link;
            $model->short_code = $short_code;
            $model->cliks = 0;
            $model->top_position = 0;
            $model->save();
        }
        return $short_code;
    }
    
    public function checkIsBot($code){
                $model = Links::find()->where(['short_code' => $code])->one();
                $model->cliks = $model->cliks+1;
                $model->save();
    }
    
    private function randomShortCode() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}
