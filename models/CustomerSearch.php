<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `app\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $meetingCount;
    public $sum_rating;
    public $latestMeeting;
    public $nextMeeting;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
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
    public function searchClues($params)
    {
        $query = $this::find()
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
//            ->from('customer')
            ->where('status="' . Customer::$CLUE . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->groupBy('customer.id');

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function searchContacts($params)
    {
        $query = $this::find()
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
//            ->from('customer')
            ->where('status="' . Customer::$CONTACT . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->groupBy('customer.id');

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function searchCustomers($params)
    {
        $query = $this::find()
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
//            ->from('customer')
            ->where('status="' . Customer::$CUSTOMER . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->groupBy('customer.id');

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function searchDeals($params)
    {
        $query = $this::find()
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
//            ->from('customer')
            ->where('status="' . Customer::$DEALING . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->groupBy('customer.id');

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
