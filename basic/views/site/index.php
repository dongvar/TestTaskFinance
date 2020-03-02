<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Финансовая информация';
?>
<?php $form = ActiveForm::begin(); ?>

    <div style="width:300px">
    <?= $form->field($requestForm, 'iinBin')->label('ИИН/БИН') ?>
    
        <?= Html::submitButton('Получить данные', ['class' => 'btn btn-primary', 'style' => 'width:200px']) ?>
    </div>
    
<?php ActiveForm::end(); ?>