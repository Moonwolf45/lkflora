<?php

namespace app\models\addition;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdditionSearch represents the model behind the search form of `app\models\addition\Addition`.
 */
class AdditionSearch extends Addition {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'type'], 'integer'],
            [['name', 'about'], 'safe'],
            [['cost'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Addition::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'cost' => $this->cost,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
