<?php

namespace app\models\payments;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class PaymentsFinanceSearch extends Payments {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'user_id', 'shop_id', 'type_service', 'service_id', 'type', 'way', 'invoice_number', 'status'], 'integer'],
            [['amount'], 'number'],
            [['date', 'invoice_date'], 'date'],
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
        $query = Payments::find()->joinWith('user')->joinWith('shop')->joinWith('tariff')
            ->joinWith('addition');

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
            'shop_id' => $this->shop_id,
            'type_service' => $this->type_service,
            'service_id' => $this->service_id,
            'type' => $this->type,
            'way' => $this->way,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'amount' => $this->amount,
            'date' => $this->date,
            'invoice_date' => $this->invoice_date,
        ]);

        return $dataProvider;
    }

}
