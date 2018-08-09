<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DealLevel;

/**
 * DealLevelSearch represents the model behind the search form of `app\models\DealLevel`.
 */
class DealLevelSearch extends DealLevel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level_number', 'created_at', 'updated_at'], 'integer'],
            [['level_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params, $getQuery = false)
    {
        $query = DealLevel::find()->orderBy('level_number ASC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'level_number' => $this->level_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'level_name', $this->level_name]);

        if ($getQuery) {
            return $query;
        } else {
            return $dataProvider;
        }
    }
}
