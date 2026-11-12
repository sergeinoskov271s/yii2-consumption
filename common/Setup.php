<?php


/**
 * Class Setup
 */
class Setup
{
    public const DATE_FORMAT = 'php:Y-m-d';
    public const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    public const TIME_FORMAT = 'php:H:i:s';

    /**
     * @param $dateStr
     * @param string $type
     * @param null $format
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function convert($dateStr, $type='date', $format = null): string
    {
        if ($type === 'datetime') {
            $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        }
        elseif ($type === 'time') {
            $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        }
        else {
            $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }


}