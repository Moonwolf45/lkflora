<?php

namespace app\models\service;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ServiceNotAgreeSearch represents the model behind the search form of `app\models\service\Service`.
 */
class ServiceNotAgreeSearch extends Service {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'shop_id', 'type_service', 'type_serviceId'], 'integer'],
            [['repeat_service', 'agree', 'deleted'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['writeoff_date', 'connection_date'], 'safe'],
            [['writeoff_amount'], 'number'],
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
        $query = Service::find()->joinWith('user')->joinWith('shop')->joinWith('tariff')
            ->joinWith('additions')->where(['agree' => Service::AGREE_FALSE]);

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
            'writeoff_amount' => $this->writeoff_amount,
            'repeat_service' => $this->repeat_service,
            'deleted' => $this->deleted,
        ]);

        if ($this->connection_date != '') {
            $query->andFilterWhere(['like', 'connection_date', Yii::$app->formatter->asDate($this->connection_date, 'yyyy-MM-dd')]);
        }

        return $dataProvider;
    }
}
