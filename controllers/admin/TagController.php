<?php

namespace app\controllers\admin;

use app\components\validators\TagValidator;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\d99b\Exception;
use Yii;
use yii\base;
use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\Tag;
use app\models\View;

use yii\db\ActiveRecord;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;
//use app\common\components\CurlGetHelpers;
use yii\helpers\BaseUrl;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use yii\web\HttpException;
use yii\db\Expression;

use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;



class TagController extends \yii\web\Controller
{
	
	public function beforeAction($action)
    {
        $this->layout = '@app/views/layouts/main-tag.php';
        return parent::beforeAction($action);
    }
	public function getViewPath(): string
    {
        return '@app/views/admin/tag';
    }
	
	
	
	
	
	
	
	
	
	
    public function actionIndex()
    {
        return $this->render('index');
    }

}
