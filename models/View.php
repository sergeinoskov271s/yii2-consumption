<?php
declare(strict_types=1);

namespace app\models;

use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table 
 *
 * @property string $cat
 * @property int $active
 */
class View extends Model
{
	public string $dt1 = '';
    public int $main_count =0 ;
    public string $cat = '';
    public string $forwhat = '';
    public string $towhom = '';
    public string $title = '';
	public int $total_summ = 0;
	public int $group_summ = 0;
	public int $group_avg = 0;
	public int $month_avg = 0;
	public int $total_summ_balance = 0;
	public int $group_summ_balance = 0;
	public int $group_summ_template = 0;
	public int $group_summ_outlay =0;
	public int $group_avg_balance = 0;
	public int $month = 0;
	public int $avg_summ = 0;
	public string $group_cat = '';
	public string $group_towhom = '';
	public string $group_forwhat = '';
	public string $group_year = '';
	public string $group_month = '';
	public int $price = 0;
	public string $id = '';
	public string $date;
	public $cats;
	public $towhoms;
	public $forwhats;
	public $budgets;
	public $monthly;
	public int $total_month_template_cat =0;
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
	public static function getTotal($provider, $columnName)
	{
		$total = 0;
		foreach ($provider as $item) {
			$total += (int)$item[$columnName];
		}
		return $total;  
	}
	
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [       
//            [['order', 'pole', 'date'],'trim'],
 //           [['order', 'date'], 'required'],
			[['avg_summ','total_summ','price'], 'integer'],
            [['dt1'], 'match', 'pattern' => '%^(?P[12][09][012][0-9])(\-(?P[01][0-9]))?(\-(?P[0-3][0-9]))?%'],
			 ['status', 'in', 'range' => ['yeas','no']],
 //           [['created_at', 'updated_at'], 'safe'],
 //           [['tab_number'], 'string', 'max' => 7],
 //           [['forwhat', 'towhom'], 'string', 'max' => 256],
            /*[['computer'], 'filter', 'filter' => function ($value) {
                return ($value === null ? '' : (string)$value);
            }],*/
      
            [['cat','avg_cat','towhom','group_towhom'], 'string', 'max' => 64],
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
         //   'id' => 'ID',
			'dt1'=>'Date',
            'forwhat' => 'for what',
            'towhom' => 'to whom', 
            'cat' => 'Cat',
            'order'=>'order pole',
			'total_summ'=>'Total of summ',
			'date'=>'Date',
			'avg_summ'=>'Avg on total of summ',
			'month'=>'Montht'
        ];
    }
}
