<?php

namespace app\models\tariff;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TariffSearch represents the model behind the search form of `app\models\Tariff`.
 */
class TariffSearch extends Tariff {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'term'], 'integer'],
            [['drop', 'status', 'maximum'], 'boolean', 'trueValue' => true, 'falseValue' => false],
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
        $query = Tariff::find()->joinWith('shops');

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cost' => $this->cost,
            'drop' => $this->drop,
            'status' => $this->status,
            'maximum' => $this->maximum,
            'term' => $this->term,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
