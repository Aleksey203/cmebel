<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

/**
 * TasksSearch represents the model behind the search form about `app\models\Tasks`.
 */
class TasksSearch extends Tasks
{

    public $ownerUser;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'order_opencart_id', 'serial_number'], 'integer'],
            [['text', 'comment', 'date_added', 'date_start', 'date_end', 'date_closed','ownerUser'], 'safe'],
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
        $query = Tasks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'order_opencart_id',
                'serial_number',
                'text',
                'date_start',
                'date_end',
                'date_closed',
                'comment',
                'ownerUser' => [
                    'asc' => [
                        'userData.username' => SORT_ASC,
                    ],
                    'desc' => [
                        'userData.username' => SORT_DESC,
                    ],
                ],
            ],
            'enableMultiSort'=>true
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_opencart_id' => $this->order_opencart_id,
            'serial_number' => $this->serial_number,
            'date_added' => $this->date_added,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'date_closed' => $this->date_closed,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);

//        $query->with('userData');

        $query->joinWith(['userData' => function ($q) {
                $q->where('userData.username LIKE "%' . $this->ownerUser . '%"');
            }]);

        return $dataProvider;
    }


    public function searchForManager($params) {
        $query = Tasks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'order_opencart_id',
                'serial_number',
                'text',
                'date_start',
                'date_end',
                'date_closed',
                'comment',
                'ownerUser' => [
                    'asc' => [
                        'userData.username' => SORT_ASC,
                    ],
                    'desc' => [
                        'userData.username' => SORT_DESC,
                    ],
                ],
            ],
            'enableMultiSort'=>true
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => Yii::$app->user->identity->id,
            'order_opencart_id' => $this->order_opencart_id,
            'serial_number' => $this->serial_number,
            'date_added' => $this->date_added,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'date_closed' => $this->date_closed,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        $query->with('orderData');


        $query->joinWith(['userData' => function ($q) {
                $q->where('userData.username LIKE "%' . $this->ownerUser . '%"');
            }]);

        return $dataProvider;
    }

}
