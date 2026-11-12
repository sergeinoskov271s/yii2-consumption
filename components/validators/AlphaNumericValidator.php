<?php
namespace app\components\validators;

use yii\validators\Validator;

class AlphaNumericValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!in_array($model->$attribute, ['USA', 'Indonesia'])) {
            $this->addError($model, $attribute, 'The country must be either "{country1}" or "{country2}".', ['country1' => 'USA', 'country2' => 'Indonesia']);
        }
    }
}
