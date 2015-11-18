<?php
/** File: UploadForm.php Date: 17.11.2015 Time: 17:43 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
use app\models\Orders;

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

	public function upload($id)
	{
		if ($this->validate()) {
			$path = Yii::getAlias('@app/runtime').'/order_files/'.$id.'/';
			if (!is_dir($path)) mkdir($path);
			$file = Orders::trunslit($this->file->baseName). '.' . $this->file->extension;
			$this->file->saveAs($path . $file);
			return true;
		} else {
			return false;
		}
	}
}