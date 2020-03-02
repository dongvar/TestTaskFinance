<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FinanceInfo;

/**
 * FinanceInfoSearch represents the model behind the search form of `app\models\FinanceInfo`.
 */
class FinanceInfoSearch extends FinanceInfo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iin_bin'], 'integer'],
            [['name_ru'], 'safe'],
            [['total_arrear', 'total_tax_arrear', 'pension_contribution_arrear', 'social_contribution_arrear', 'social_health_insurance_arrear'], 'number'],
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
        $query = FinanceInfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'iin_bin' => $this->iin_bin,
            'total_arrear' => $this->total_arrear,
            'total_tax_arrear' => $this->total_tax_arrear,
            'pension_contribution_arrear' => $this->pension_contribution_arrear,
            'social_contribution_arrear' => $this->social_contribution_arrear,
            'social_health_insurance_arrear' => $this->social_health_insurance_arrear,
        ]);

        $query->andFilterWhere(['ilike', 'name_ru', $this->name_ru]);

        return $dataProvider;
    }
}
