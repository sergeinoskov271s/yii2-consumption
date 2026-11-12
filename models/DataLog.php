<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_log".
 *
 * @property int $id ID dataLog
 * @property int $user_id
 * @property string $table_name
 * @property string|null $app_id
 * @property int $type_key
 * @property string|null $record_id
 * @property string|null $model_name
 * @property string|null $data_prev
 * @property string $created_at data and time
 * @property string $data_current
 */
class DataLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'table_name', 'created_at', 'data_current'], 'required'],
            [['user_id', 'type_key'], 'integer'],
            [['data_prev', 'data_current'], 'string'],
            [['created_at'], 'safe'],
            [['table_name', 'app_id', 'record_id', 'model_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'table_name' => 'Table Name',
            'app_id' => 'App ID',
            'type_key' => 'Type Key',
            'record_id' => 'Record ID',
            'model_name' => 'Model Name',
            'data_prev' => 'Data Prev',
            'created_at' => 'Created At',
            'data_current' => 'Data Current',
        ];
    }
}
