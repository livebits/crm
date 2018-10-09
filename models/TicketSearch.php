<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ticket;
use yii\db\Query;

/**
 * TicketSearch represents the model behind the search form of `app\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    public $department_name;
    public $deal_subject;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'deal_id', 'department', 'created_at', 'updated_at'], 'integer'],
            [['department_name', 'deal_subject'], 'string'],
            [['title', 'body'], 'safe'],
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
    public function search($params, $departments = null, $tickets_id = null, $fromApi = false)
    {
        if($fromApi) {
            $queryObj = new Query();
        } else {
            $queryObj = $this::find();
        }

        $query = $queryObj
            ->select(['ticket.*', 'department.name as department_name', 'deal.subject as deal_subject'])
            ->from('ticket')
            ->leftJoin('deal', 'deal.id=ticket.deal_id')
            ->leftJoin('department', 'department.id = ticket.department')
            ->groupBy('ticket.id');


        // add conditions that should always apply here
        if(isset($departments)) {
            $query = $query->orWhere('department IN (' . implode(',', $departments)  . ')');
        }

        if(isset($tickets_id)) {
            $query = $query->orWhere('ticket.id IN (' . implode(',', $tickets_id)  . ')');
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
            'user_id' => $this->user_id,
            'deal_id' => $this->deal_id,
            'department' => $this->department,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body]);

        $query = $query->andWhere('reply_to IS NULL');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
