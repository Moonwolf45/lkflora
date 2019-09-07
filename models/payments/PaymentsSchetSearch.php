<?php

namespace app\models\payments;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class PaymentsSchetSearch extends Payments {

    /**
     * {@inheritdoc}
     */
    public function rules ()
    {
        return [
            [['id', 'user_id', 'invoice_number', 'status'], 'integer'],
            [['amount'], 'number'],
            [['date', 'invoice_date'], 'date'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios () {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search ($params) {
        $query = Payments::find()->where(['type' => Payments::TYPE_REFILL])->andWhere(['!=', 'invoice_number', ''])
            ->joinwith('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['invoice_number' => SORT_DESC]],
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
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'amount' => $this->amount,
            'date' => $this->date,
            'invoice_date' => $this->invoice_date,
        ]);
        return $dataProvider;
    }

}
