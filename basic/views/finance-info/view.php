<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\FinanceInfo */

$this->title = $financeInfoModel->iin_bin;
$this->params['breadcrumbs'][] = ['label' => 'Сохранённые данные', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>


<h3>Сведения об отсутствии (наличии) задолженности, учет по которым ведется в органах государственных доходов</h3>
<br>
<table class="info1">
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('name_ru') ?>:</td>
      <td><?= $financeInfoModel->name_ru ?></td>
    </tr>
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('iin_bin') ?>:</td>
      <td><?= $financeInfoModel->iin_bin ?></td>
    </tr>
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('total_arrear') ?>:</td>
      <td><?= $financeInfoModel->total_arrear ?></td>
    </tr>    
</table>
<br>

<table class="info2">
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('total_tax_arrear') ?>:</td>
      <td><?= $financeInfoModel->total_tax_arrear ?></td>
    </tr>
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('pension_contribution_arrear') ?>:</td>
      <td><?= $financeInfoModel->pension_contribution_arrear ?></td>
    </tr>
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('social_contribution_arrear') ?>:</td>
      <td><?= $financeInfoModel->social_contribution_arrear ?></td>
    </tr>    
    <tr>
      <td><?= $financeInfoModel->getAttributeLabel('social_health_insurance_arrear') ?>:</td>
      <td><?= $financeInfoModel->social_health_insurance_arrear ?></td>
    </tr>    
</table>


<br>
<h5 class="header5">Таблица задолженностей по органам государственных доходов</h5>

<? foreach($taxOrgInfoModel->find()->where(['iin_bin' => $financeInfoModel->iin_bin])->all() as $taxOrgInfo) : ?>

<div class="iblock">

<h4 class="header4"><?= $taxOrgInfo->getAttributeLabel('name_ru') ?> <?= $taxOrgInfo->name_ru ?> <?= $taxOrgInfo->getAttributeLabel('char_code') ?> <?= $taxOrgInfo->char_code ?></h4>
<br>
<div class="line"><?= $taxOrgInfo->getAttributeLabel('report_acrual_date') ?> <b><?= date('Y-m-d', floor($taxOrgInfo->report_acrual_date / 1000)) ?></b></div>
<div class="line"><?= $taxOrgInfo->getAttributeLabel('total_arrear') ?>: <b><?= $taxOrgInfo->total_arrear ?></b></div>
<br>

<table class="info2">
    <tr>
      <td><?= $taxOrgInfo->getAttributeLabel('total_tax_arrear') ?>:</td>
      <td><?= $taxOrgInfo->total_tax_arrear ?></td>
    </tr>
    <tr>
      <td><?= $taxOrgInfo->getAttributeLabel('pension_contribution_arrear') ?>:</td>
      <td><?= $taxOrgInfo->pension_contribution_arrear ?></td>
    </tr>
    <tr>
      <td><?= $taxOrgInfo->getAttributeLabel('social_contribution_arrear') ?>:</td>
      <td><?= $taxOrgInfo->social_contribution_arrear ?></td>
    </tr>    
    <tr>
      <td><?= $taxOrgInfo->getAttributeLabel('social_health_insurance_arrear') ?>:</td>
      <td><?= $taxOrgInfo->social_health_insurance_arrear ?></td>
    </tr>    
</table>

<br>
<h5 class="header5">Таблица задолженностей по налогоплательщику и его структурным подразделениям</h5>


<? foreach($taxPayerInfoModel->find()->where(['tax_org_info_id' => $taxOrgInfo->id])->all() as $taxPayerInfo) : ?>

<table class="info1">
    <tr>
      <td><?= $taxPayerInfo->getAttributeLabel('name_ru') ?>:</td>
      <td><?= $taxPayerInfo->name_ru ?></td>
    </tr>
    <tr>
      <td><?= $taxPayerInfo->getAttributeLabel('iin_bin') ?>:</td>
      <td><?= $taxPayerInfo->iin_bin ?></td>
    </tr>
    <tr>
      <td><?= $taxPayerInfo->getAttributeLabel('total_arrear') ?>:</td>
      <td><?= $taxPayerInfo->total_arrear ?></td>
    </tr>    
</table>

<br>
<table class="info3">
  <thead>
    <tr>
      <th><?= $bccArrearsInfoModel->getAttributeLabel('bcc') ?></th>      
      <th><?= $bccArrearsInfoModel->getAttributeLabel('tax_arrear') ?></th>      
      <th><?= $bccArrearsInfoModel->getAttributeLabel('poena_arrear') ?></th>      
      <th><?= $bccArrearsInfoModel->getAttributeLabel('percent_arrear') ?></th>      
      <th><?= $bccArrearsInfoModel->getAttributeLabel('fine_arrear') ?></th>      
      <th><?= $bccArrearsInfoModel->getAttributeLabel('total_arrear') ?></th>      
    </tr>
   </thead>
   <tbody>
   

<? foreach($bccArrearsInfoModel->find()->where(['tax_payer_info_id' => $taxPayerInfo->id])->all() as $bccArrearsInfo) : ?>   
   
    <tr>
      <td><?= $bccArrearsInfo->bcc ?> <?=$bccArrearsInfo->bcc_name_ru ?></td>
      <td><?= $bccArrearsInfo->tax_arrear ?></td>
      <td><?= $bccArrearsInfo->poena_arrear ?></td>
      <td><?= $bccArrearsInfo->percent_arrear ?></td>
      <td><?= $bccArrearsInfo->fine_arrear ?></td>
      <td><?= $bccArrearsInfo->total_arrear ?></td>
    </tr>

<? endforeach; ?>   
    
  </tbody>
</table>

<? endforeach; ?>
</div>
<? endforeach; ?>




<div class="finance-info-view">
    
    <p>        
        <?= Html::a('Удалить', ['delete', 'id' => $financeInfoModel->iin_bin], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
