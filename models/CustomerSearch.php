<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Customer;
use webvimark\modules\UserManagement\models\User;

/**
 * CustomerSearch represents the model behind the search form of `app\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $FirstName;
    public $LastName;
    public $Mobile;
    public $SourceName;

    public $meetingCount;
    public $sum_rating;
    public $latestMeeting;
    public $nextMeeting;

    public $doneTasks;
    public $allTasks;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'Mobile', 'created_at', 'updated_at'], 'integer'],
            [['FirstName', 'LastName', 'SourceName'], 'string'],
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
    public function searchClues($params, $fromApi = false)
    {
        if($fromApi){
            $thisQuery = new yii\db\Query; 
        } else {
            $thisQuery = $this::find();
        }
        $query = $thisQuery
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating', 'src.name as SourceName',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
            ->from('customer')
            ->where('status="' . Customer::$CLUE . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->leftJoin('source as src', 'customer.source = src.id')
            ->groupBy('customer.id');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('customer.id IN (' . implode(',', $customer_ids) . ')');
        }

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
//            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'customer.id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->FirstName])
            ->andFilterWhere(['like', 'lastName', $this->LastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['=', 'mobile', $this->Mobile])
            ->andFilterWhere(['=', 'phone', $this->phone])
            ->andFilterWhere(['=', 'source', $this->source])
            ->andFilterWhere(['like', 'description', $this->description]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       
        return $dataProvider;
    }

    public function searchOffCustomers($params, $getQuery = false)
    {
        $query = $this::find()
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
//            ->from('customer')
            ->where('status="' . Customer::$OFF_CUSTOMER . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->groupBy('customer.id');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('customer.id IN (' . implode(',', $customer_ids) . ')');
        }

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'customer.id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->FirstName])
            ->andFilterWhere(['like', 'lastName', $this->LastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->Mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->Source])
            ->andFilterWhere(['like', 'description', $this->description]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($getQuery) {
            return $query;
        } else {
            return $dataProvider;
        }
    }

    public function searchContacts($params, $getQuery = false)
    {
        $query = $this::find()
            ->select(['customer.*', 'SUM(cm.rating) as sum_rating',
                'MAX(cm.created_at) as latestMeeting', 'MAX(cm.next_date) as nextMeeting',
                'COUNT(cm.id) as meetingCount'])
//            ->from('customer')
            ->where('status="' . Customer::$CLUE . '"')
            ->orWhere('status="' . Customer::$CUSTOMER . '"')
            ->orWhere('status="' . Customer::$OFF_CUSTOMER . '"')
            ->leftJoin('meeting as cm', 'cm.customer_id = customer.id')
            ->groupBy('customer.id');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('customer.id IN (' . implode(',', $customer_ids) . ')');
        }

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'customer.id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->FirstName])
            ->andFilterWhere(['like', 'lastName', $this->LastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->Mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->Source])
            ->andFilterWhere(['like', 'description', $this->description]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($getQuery) {
            return $query;
        } else {
            return $dataProvider;
        }
    }

    public function searchCustomers($params, $getQuery = false)
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
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('customer.id IN (' . implode(',', $customer_ids) . ')');
        }

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'customer.id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->FirstName])
            ->andFilterWhere(['like', 'lastName', $this->LastName])
            ->andFilterWhere(['like', 'companyName', $this->companyName])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'mobile', $this->Mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'source', $this->Source])
            ->andFilterWhere(['like', 'description', $this->description]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($getQuery) {
            return $query;
        } else {
            return $dataProvider;
        }
    }

    public function searchDeals($params, $getQuery = false)
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

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($getQuery) {
            return $query;
        } else {
            return $dataProvider;
        }
    }
}
