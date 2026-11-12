<?php
declare(strict_types=1);

namespace app\models;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class Budget extends ActiveRecord
{

    /**
     * @var false|mixed|string|null
     */
   /* private Integer $updated_at;
    private Integer $created_at;*/
    public string $updated_formatted = '';
    public string $created_formatted = '';

//    private $created_formatted;
    
    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute
     * `firstName`, we can declare a label `First Name` which is more user-friendly and can
     * be displayed to end users.
     *
     * By default an attribute label is generated using [[generateAttributeLabel()]].
     * This method allows you to explicitly specify attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to
     * merge the parent labels with child labels using functions such as `array_merge()`.
     *
     * @return array attribute labels (name => label)
     * @see generateAttributeLabel()
     */

    public static function tableName()
    {
        return 'budget';
    }

    /**
     * @return array|array[]
     */
    /*public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created_at'],

                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }*/
	public static function getTotal($provider, $columnName)
	{
		$total = 0;
			foreach ($provider as $item) {
			$total += $item[$columnName];
		}
		return $total;  
	}

    public function rules()
    {
        return [
	//		[ [ 'schedule'] , 'safe' ],
            [['towhom', 'forwhat', 'cat','dt1','id'], 'trim'],
            [['towhom', 'forwhat'], 'string', 'max' => 64],
            [['cat'], 'string', 'max' => 32],
			[['cat', 'price','dt1'], 'required'],
 //           ['dt1', 'date', 'format' => 'yyyy-MM-dd','message' => 'Format is wrong'],
#            ['updated_formatted', 'date', 'format' => 'php:Y-m-d H:i:s','message' => 'Format is wrong'],
        ];
    }
	

    /**
     * @return ActiveQuery
     */
    public function attributeLabels()
    {
        return [
			'id' => 'id',
            'dt1' => 'date',
			'date' => 'date',
            'towhom' => 'to whom',
            'forwhat' => 'for what',
            'cat' => 'Cat',
			'price'=>'Price',
			'group_summ'=>'Group Summ'
        ];
    }
	/* public function afterFind() {
		parent::afterFind();
		$this->schedule = \yii\helpers\Json::decode($this->schedule);
	} */
}
