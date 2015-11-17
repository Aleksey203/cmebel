<?php
/** File: UploadForm.php Date: 17.11.2015 Time: 17:43 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
	/**
	 * @var UploadedFile
	 */
	public $file;

	public function rules()
	{
		return [
			[['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, pdf, doc, txt, xls, wav, mp3, mp4, avi'],
		];
	}

	public function upload()
	{
		if ($this->validate()) {
			$this->file->saveAs(Yii::getAlias('@webroot').'/uploads/' .$this->file->baseName . '.' . $this->file->extension);
			return true;
		} else {
			return false;
		}
	}
}