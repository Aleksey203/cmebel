<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `app\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_opencart_id', 'version', 'client_id', 'status_id'], 'integer'],
            [['total'], 'number'],
            [['date_added', 'date_modified'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find()->select('orders.*, MAX(version)')->groupBy('order_opencart_id')->orderBy('order_opencart_id desc, version desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_opencart_id' => $this->order_opencart_id,
            'version' => $this->version,
            'status_id' => $this->status_id,
            'total' => $this->total,
        ]);
	    if(!empty($params['Orders']['from_date']) AND !empty($params['Orders']['to_date'])) {
		    $params['Orders']['from_date'] =  Yii::$app->formatter->asDate($params['Orders']['from_date'], 'php:Y-m-d');
		    $params['Orders']['to_date'] =  Yii::$app->formatter->asDate($params['Orders']['to_date'], 'php:Y-m-d');
	    $query->andWhere('DATE(' . Orders::tableName() . '.date_added) BETWEEN "' . $params['Orders']['from_date'] . '" AND "' . $params['Orders']['to_date'] . '"
	                        OR DATE(' . Orders::tableName() . '.date_modified) BETWEEN "' . $params['Orders']['from_date'] . '" AND "' . $params['Orders']['to_date'] . '"');
	    }
	    else {
			if(!empty($params['Orders']['from_date'])) {
				$params['Orders']['from_date'] =  Yii::$app->formatter->asDate($params['Orders']['from_date'], 'php:Y-m-d');
				$query->andWhere('DATE(' . Orders::tableName() . '.date_added) >= "' . $params['Orders']['from_date'] . '" OR
	                                                       DATE(' . Orders::tableName() . '.date_modified) >= "' . $params['Orders']['from_date'] . '"');
			}
		    if(!empty($params['Orders']['to_date'])) {
			    $params['Orders']['to_date'] =  Yii::$app->formatter->asDate($params['Orders']['to_date'], 'php:Y-m-d');
			    $query->andWhere('DATE(' . Orders::tableName() . '.date_added) <= "' . $params['Orders']['to_date'] . '" OR
	                                                       DATE(' . Orders::tableName() . '.date_modified) <= "' . $params['Orders']['to_date'] . '"');
			}
		}

	    if(!empty($params['client'])) $query->andWhere(Clients::tableName() .".name LIKE '%".$params["client"]."%'");


        return $dataProvider;
    }
}
