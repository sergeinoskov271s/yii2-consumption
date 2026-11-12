<?php
namespace common\behaviors;

use yii;
use yii\base\Behavior;

class Slug extends Behavior
{
    public $iniciali = null;

    public function events()
    {    
            return [    
                yii\web\Controller::EVENT_BEFORE_ACTION => 'getMyMetod'    
            ];
    }    
    
    public function getMyMetod(){
			Yii::info('getMyMetod');
			yii::$app->params['fio'] = $this->iniciali . "ksl";    
    }
}