<?php


namespace app\models;
use yii\web\UploadedFile;

class UploadForm extends \yii\base\Model
{
    /**
     * @var UploadedFile file attribute
     */
    public  $file;

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }

    /*public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('runtime/upload/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }*/

}