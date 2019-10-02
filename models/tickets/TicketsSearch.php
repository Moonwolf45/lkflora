<?php

namespace app\models\tickets;


use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketsSearch represents the model behind the search form of `app\models\tickets\Tickets`.
 */
class TicketsSearch extends Tickets {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'status'], 'integer'],
            ['new_text', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => false],
            [['subject'], 'string', 'max' => 255],

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
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params) {
        $query = Tickets::find()->with('user');

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'new_text' => $this->new_text,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject]);

        return $dataProvider;
    }
}
