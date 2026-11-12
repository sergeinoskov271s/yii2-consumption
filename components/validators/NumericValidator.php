<?php


namespace app\components\validators;


class NumericValidator extends \yii\validators\NumberValidator
{
    public function validateAttribute($model, $attribute)
    {
        if (strlen($model->$attribute) > 7) {
            $this->addError($model, $attribute,
                'Too long attribute');
        }
    }
}