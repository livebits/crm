<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Deal;
use webvimark\modules\UserManagement\models\User;
use yii\db\Query;

/**
 * DealSearch represents the model behind the search form of `app\models\Deal`.
 */
class DealSearch extends Deal
{

    public $meetingCount;
    public $sum_rating;
    public $latestMeeting;
    public $nextMeeting;
    public $firstName;
    public $lastName;
    public $mobile;
    public $levelName;
    public $customerName;

    public $doneTasks;
    public $allTasks;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'price', 'level', 'created_at', 'updated_at'], 'integer'],
            [['subject'], 'safe'],
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
    public function search($params, $customer_id, $getQuery = false)
    {
        $query = $this::find()
            ->select(['deal.*', 'cu.firstName', 'cu.lastName', 'cu.mobile', 'deal_level.level_name as levelName',
                'SUM(m.rating) as sum_rating', 'MAX(m.created_at) as latestMeeting',
                'MAX(m.next_date) as nextMeeting', 'COUNT(m.id) as meetingCount'])
            ->from('deal')
            ->leftJoin('customer as cu', 'cu.id=deal.customer_id')
            ->leftJoin('meeting as m', 'm.deal_id=deal.id')
            ->leftJoin('deal_level', 'deal_level.id=deal.level')
            ->where('deal.customer_id=' . $customer_id)
            ->groupBy('deal.id');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('cu.id IN (' . implode(',', $customer_ids) . ')');
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
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'price' => $this->price,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($getQuery) {
            return $query;

        } else {
            return $dataProvider;
        }
    }

    public function searchAll($params, $getQuery = false)
    {
        $query = $this::find()
            ->select(['deal.*', 'cu.id as customerName', 'cu.firstName', 'cu.lastName', 'cu.mobile', 'deal_level.level_name as levelName',
                'SUM(m.rating) as sum_rating', 'MAX(m.created_at) as latestMeeting',
                'MAX(m.next_date) as nextMeeting', 'COUNT(m.id) as meetingCount'])
            ->from('deal')
            ->leftJoin('customer as cu', 'cu.id=deal.customer_id')
            ->leftJoin('meeting as m', 'm.deal_id=deal.id')
            ->leftJoin('deal_level', 'deal_level.id=deal.level')
            ->groupBy('deal.id');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('cu.id IN (' . implode(',', $customer_ids) . ')');
        }

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'deal.customer_id' => $this->customer_id,
            'price' => $this->price,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['=', 'deal.customer_id', $this->customer_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($getQuery) {
            return $query;

        } else {
            return $dataProvider;
        }
    }

    public function searchUserDeals($params, $user_deals_id, $fromApi = false)
    {
        if($fromApi){
            $query = new Query();
        } else {
            $query = $this::find();
        }
        $query = $query
            ->select(['deal.*', 'cu.id as customerName', 'cu.firstName', 'cu.lastName', 'cu.mobile', 'deal_level.level_name as levelName',
                'SUM(m.rating) as sum_rating', 'MAX(m.created_at) as latestMeeting',
                'MAX(m.next_date) as nextMeeting', 'COUNT(m.id) as meetingCount'])
            ->from('deal')
            ->leftJoin('customer as cu', 'cu.id=deal.customer_id')
            ->leftJoin('meeting as m', 'm.deal_id=deal.id')
            ->leftJoin('deal_level', 'deal_level.id=deal.level')
            ->groupBy('deal.id');

        // add conditions that should always apply here
        if(count($user_deals_id) > 0) {

            $query->andWhere('deal.id IN (' . implode(',', $user_deals_id) . ')');
        }

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'deal.customer_id' => $this->customer_id,
            'price' => $this->price,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['=', 'deal.customer_id', $this->customer_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
