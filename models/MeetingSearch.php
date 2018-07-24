<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Meeting;
use webvimark\modules\UserManagement\models\User;

/**
 * MeetingSearch represents the model behind the search form of `app\models\Meeting`.
 */
class MeetingSearch extends Meeting
{
    public $username;
    public $imagesCount;
    public $audiosCount;
    public $attachsCount;
    public $customer_code;
    public $customer_name;
    public $deal_subject;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'customer_id', 'created_at', 'updated_at', 'next_date'], 'integer'],
            [['content'], 'safe'],
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
    public function search($params, $customer_id = null)
    {
        $query = $this::find()
            ->select(['user.username', 'meeting.*'
                , 'COUNT(CASE WHEN media.type="' . Media::$IMAGE . '" THEN 1 END) as imagesCount'
                , 'COUNT(CASE WHEN media.type="' . Media::$AUDIO . '" THEN 1 END) as audiosCount'
                , 'COUNT(CASE WHEN media.type="' . Media::$OTHER . '" THEN 1 END) as attachsCount'
                    ])
            ->from('meeting')
            ->leftJoin('user', 'user.id=meeting.user_id')
            ->leftJoin('media', 'media.meeting_id=meeting.id')
            ->groupBy('meeting.id')
            ->orderBy('meeting.id DESC');

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
            'customer_id' => $customer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'next_date' => $this->next_date,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    public function searchCustomersLates($params, $lateMeetingsIds)
    {
        $query = $this::find()
            ->select(['user.username', 'meeting.*', 'customer.id as customer_code',
                'CONCAT(customer.firstName, " ", customer.lastName) as customer_name'
                    ])
            ->from('meeting')
            ->leftJoin('user', 'user.id=meeting.user_id')
            ->leftJoin('customer', 'customer.id=meeting.customer_id')
            ->leftJoin('media', 'media.meeting_id=meeting.id')
            ->where('meeting.id IN ('.$lateMeetingsIds.')')
            ->groupBy('meeting.id')
            ->orderBy('meeting.id DESC');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('customer.id IN (' . implode(',', $customer_ids) . ')');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => [
                //Normal columns
                'next_date',
            ],],
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
//            'customer_id' => $this->customer_id,
//            'customer.firstName' => $this->customer_name,
//            'customer.lastName' => $this->customer_name,
            'next_date' => $this->next_date,
        ]);

        return $dataProvider;
    }

    public function searchDealsLates($params, $lateMeetingsIds)
    {
        $query = $this::find()
            ->select(['user.username', 'meeting.*', 'customer.id as customer_code',
                'CONCAT(customer.firstName, " ", customer.lastName) as customer_name',
                'deal.subject as deal_subject'
                    ])
            ->from('meeting')
            ->leftJoin('user', 'user.id=meeting.user_id')
            ->leftJoin('deal', 'deal.id=meeting.deal_id')
            ->leftJoin('customer', 'customer.id=deal.customer_id')
            ->leftJoin('media', 'media.meeting_id=meeting.id')
            ->where('meeting.id IN ('.$lateMeetingsIds.')')
            ->groupBy('meeting.id')
            ->orderBy('meeting.id DESC');

        // add conditions that should always apply here
        $user = User::getCurrentUser();
        if(!Yii::$app->user->isSuperadmin  || !$user::hasRole(['Admin'])) {

            $customer_ids = \app\models\User::getSubCustomers(true);
            $query->andWhere('customer.id IN (' . implode(',', $customer_ids) . ')');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => [
                //Normal columns
                'next_date',
            ],],
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
//            'customer_id' => $this->customer_id,
//            'customer.firstName' => $this->customer_name,
//            'customer.lastName' => $this->customer_name,
            'next_date' => $this->next_date,
        ]);

        return $dataProvider;
    }

    public function searchForDeals($params, $deal_id = null)
    {
        $query = $this::find()
            ->select(['user.username', 'meeting.*'
                , 'COUNT(CASE WHEN media.type="' . Media::$IMAGE . '" THEN 1 END) as imagesCount'
                , 'COUNT(CASE WHEN media.type="' . Media::$AUDIO . '" THEN 1 END) as audiosCount'
                , 'COUNT(CASE WHEN media.type="' . Media::$OTHER . '" THEN 1 END) as attachsCount'
            ])
            ->from('meeting')
            ->leftJoin('user', 'user.id=meeting.user_id')
            ->leftJoin('media', 'media.meeting_id=meeting.id')
            ->groupBy('meeting.id')
            ->orderBy('meeting.id DESC');

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
            'deal_id' => $deal_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'next_date' => $this->next_date,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
