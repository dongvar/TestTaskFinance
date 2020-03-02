<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FinanceInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сохранённые данные';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-info-index">

    <p>
        <?= Html::a('Получить новые данные', ['/site/index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'contentOptions'=>['style'=>'width:30px']],
            ['attribute'=>'iin_bin', 'contentOptions'=>['style'=>'width:20%']],
            'name_ru',            
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}&nbsp&nbsp{delete}', 'contentOptions'=>['style'=>'width:56px'] ],            
        ],
    ]); ?>


</div>
