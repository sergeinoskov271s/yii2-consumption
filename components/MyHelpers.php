<?php
namespace app\components;
// namespace app\components; // For Yii2 Basic (app folder won't actually exist)
class MyHelpers
{
    public static function hello($name) {
        return "Hello $name";
    }
}