<?php
namespace app\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use Yii;

class MyBehavior extends Behavior
{
    public $prop1;

    private $_prop2;

    public function getProp2()
    {
        return $this->_prop2;
    }

    public function setProp2($value)
    {
        $this->_prop2 = $value;
    }

    public function foo()
    {
        // ...
    }
	
	public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
			ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
			
        ];
    }

    public function beforeValidate($event)
    {
		Yii::info('+++++');
        // ...
    }
	public function beforeAction($event)
    {
		Yii::info('+++++');
        // ...
    }
	public function afterFind($event){
//		Yii::info($event);
	}
}
