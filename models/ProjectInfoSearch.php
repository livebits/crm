<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectInfo;

/**
 * ProjectInfoSearch represents the model behind the search form of `app\models\ProjectInfo`.
 */
class ProjectInfoSearch extends ProjectInfo
{
    public $project_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'created_at', 'updated_at'], 'integer'],
            [['publish_version', 'package_name', 'sign_file', 'keystore', 'api_key', 'key_alias_password'], 'safe'],
            [['project_name'], 'string'],
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
    public function search($params)
    {
        $query = $this::find()
            ->select(['project_info.*', 'project.title as project_name'])
            ->leftJoin('project', 'project.id=project_info.project_id')
            ->groupBy('project_info.id');

        // add conditions that should always apply here

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        if(isset($params['ProjectInfoSearch']['user_id'])) {
            $query->andWhere(['user_id' => $params['ProjectInfoSearch']['user_id']]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'project_info.id' => $this->id,
            'project_id' => $this->project_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'publish_version', $this->publish_version])
            ->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'sign_file', $this->sign_file])
            ->andFilterWhere(['like', 'keystore', $this->keystore])
            ->andFilterWhere(['like', 'api_key', $this->api_key])
            ->andFilterWhere(['like', 'key_alias_password', $this->key_alias_password]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
