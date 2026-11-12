<?php
declare(strict_types=1);

namespace app\models;

use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html; 

/**
 * This is the model class for table 
 *
 * @property string $cat
 * @property int $active
 */
class BudgetFormEdit extends Model
{
 //   public string $year = '';
	public string $dt1 = '2025-10-01';
    public int $price =0 ;
    public string $cat = '';
    public string $forwhat = '';
    public string $towhom = '';
	public string $id = '';

   /*  public static function tableName()
    {
        return 'outlay';
    } */
    /*protected function beforeValidate() {
        $this->content = trim($this->content);
        return parent::beforeValidate();
    }*/

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * Child classes may override this method to specify the behaviors they want to behave as.
     *
     * The return value of this method should be an array of behavior objects or configurations
     * indexed by behavior names. A behavior configuration can be either a string specifying
     * the behavior class or an array of the following structure:
     *
     * ```php
     * 'behaviorName' => [
     *     'class' => 'BehaviorClass',
     *     'property1' => 'value1',
     *     'property2' => 'value2',
     * ]
     * ```
     *
     * Note that a behavior class must extend from [[Behavior]]. Behaviors can be attached using a name or anonymously.
     * When a name is used as the array key, using this name, the behavior can later be retrieved using [[getBehavior()]]
     * or be detached using [[detachBehavior()]]. Anonymous behaviors can not be retrieved or detached.
     *
     * Behaviors declared in this method will be attached to the component automatically (on demand).
     *
     * @return array the behavior configurations.
     */
   /* public function behaviors()
    {
        return [
             'timestamp' => [
                 'class' => TimestampBehavior::class,
                 'attributes' => [
                     ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                     ActiveRecord::EVENT_BEFORE_UPDATE => 'created_at',
                 ],
//                 'value' => function() { return date('U'); // unix timestamp
 //                },
             ],
        ];
    }*/

    public function init() {
        parent::init();
    }

    /**
     * This method is invoked before validation starts.
     * The default implementation raises a `beforeValidate` event.
     * You may override this method to do preliminary checks before validation.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return bool whether the validation should be executed. Defaults to true.
     * If false is returned, the validation will stop and the model is considered invalid.
     */
    public function beforeValidate()
    {
    /*    if ($this->office_id == null) {
            $this->office_id = 0;
        }*/
        return parent::beforeValidate();
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [       
            [['towhom', 'forwhat', 'dt1','price'],'trim'],
            [['cat', 'date'], 'required'],
//			[[ 'date'], 'integer'],
            [['dt1'], 'match', 'pattern' => '/^([12][09][0-9][0-9])(\-([01][0-9]))?(\-([0-3][0-9]))?$/'],
            [['forwhat', 'towhom'], 'string', 'max' => 256],
            /*[['computer'], 'filter', 'filter' => function ($value) {
                return ($value === null ? '' : (string)$value);
            }],*/
			[['cat'], 'string', 'max' => 32],
        /*    ['username', 'match', 'pattern' => '/^[a-zA-Z0-9\-]{3,64}$/i']*/
           /* [['org_unit'], 'default', 'value' => function ($model, $attribute) {
                return date('Y-m-d', strtotime($attribute === 'to' ? '+3 days' : '+6 days'));
            }],*/
            /*[['username','tab_number'], 'unique','when' => function ($model, $attribute) {
                return $model->{$attribute} !== $model->getOldAttribute($attribute);
//					return true;
            }],*/
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'dt1'=>'Date',
            'forwhat' => 'for what',
            'towhom' => 'to whom', 
            'cat' => 'Cat',
			'price' => 'Price',

        ];
    }
}
