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
            [['id', 'tariff_id', 'user_id', 'deleted'], 'integer'],
            [['address'], 'safe'],
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
        $query = Shops::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tariff_id' => $this->tariff_id,
            'user_id' => $this->user_id,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
