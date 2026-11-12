<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Model;
use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\IntegrityException;
use yii\db\Expression;
use yii\behaviors\AttributeTypecastBehavior; 
use yii\helpers\VarDumper;
/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public string $username = '';
    public string $password ='';
	public string $email ='';
    public $rememberMe = true;
	public object $user;
	public int $status = 1;
	public int $id = 0;
	public $created_at;
	public $updated_at;
	public $role;



    private $_user = false;

    /**
     * This method is invoked before validation starts.
     * The default implementation raises a `beforeValidate` event.
     * You may override this method to do preliminary checks before validation.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return bool whether the validation should be executed. Defaults to true.
     * If false is returned, the validation will stop and the model is considered invalid.
     */
    public function beforeValidate() {
		$this->content = trim($this->content);
		return parent::beforeValidate();
	}
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
 //           ['username', 'unique', 'targetClass' => UserModel::class, 'message' => Yii::t('yii2mod.user', 'This username has already been taken.')],
			['username', 'unique', 'targetClass' => '\app\models\User','message' => 'This username has already been taken.'],
			['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 1],
			['status', 'required'],
            ['status',  'integer', 'min' => 0],
		/* 	['pass', 'match', 'pattern'=>'/^[a-z0-9_-]{6,20}$/'], */
			/* ['pass', 'compare', 'compareAttribute' => 'passCompare'] */
 //          	['role', 'required'],
			/* [['username', 'pass'], 'required', 'on'=>'login'], */
        ];
    }

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
    public function behaviors()
	{
		return [
			/* 'timestamp' => [
				'class' => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
				],
				'value' => new Expression('NOW()'),
			], */
			/* 'typecast' => [
                'class' => AttributeTypecastBehavior::className(),
                'attributeTypes' => [
                    'id' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'status' => AttributeTypecastBehavior::TYPE_INTEGER,//TYPE_STRING FLOAT
                ],
 //               'typecastAfterValidate' => true,
 //               'typecastBeforeSave' => false,
  //              'typecastAfterFind' => false,
            ], */
		];
	}

    /**
     * @return \app\models\User|null
     */
    public function signup()
    {
//		Yii::info(VarDumper::export($this->attributes));
//        if (!$this->validate()) {
 //           return null;
 //       }
		$this->user = new User;
		$this->user->scenario = 'register';
        $this->user->setAttributes($this->attributes);
        $this->user->setPassword($this->password);
		$this->user->setPasswordResetToken();
		$this->user->validate();
		
		$this->user->generateAuthKey();
//		Yii::info(VarDumper::dumpAsString(array('q'=>4))); 
//		Yii::info(VarDumper::dumpAsString('$this->user->password_hash'.$this->user->password_hash));
//		Yii::info(VarDumper::dump($this->user));
		$this->user->save();
		return $this->user;
    }
    /**
     * @return \app\models\User
     */
    public function getUser()
    {
		$auth = Yii::$app->authManager;

        $auth->removeAll();
        return $this->user;
    }
}
