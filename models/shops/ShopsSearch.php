<?php

namespace app\models\shops;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShopsSearch represents the model behind the search form of `app\models\shops\Shops`.
 */
class ShopsSearch extends Shops {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'address'], 'safe'],
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
        $query = Shops::find()->with('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
