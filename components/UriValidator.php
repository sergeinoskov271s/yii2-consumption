<?php
namespace app\components;

use yii\validators\Validator;

class UriValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
		$this->addError($model, $attribute, 'Uri not unique.');
       /*  if (!in_array($model->$attribute, ['uri'])) {
            $this->addError($model, $attribute, 'The country must be either "USA" or "Web".');
        } */
    }
}
