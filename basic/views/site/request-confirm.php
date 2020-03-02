<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Новые данные';
$this->params['breadcrumbs'][] = $this->title;
?>

<h3>Сведения об отсутствии (наличии) задолженности, учет по которым ведется в органах государственных доходов</h3>
<br>
<table class="info1">
    <tr>
      <td>Наименование налогоплательщика:</td>
      <td><?= $financeData['nameRu'] ?></td>
    </tr>
    <tr>
      <td>ИИН/БИН налогоплательщика:</td>
      <td><?= $financeData['iinBin'] ?></td>
    </tr>
    <tr>
      <td>Всего задолженности (тенге):</td>
      <td><?= number_format($financeData['totalArrear'], 2, '.', ' ') ?></td>
    </tr>    
</table>
<br>

<table class="info2">
    <tr>
      <td>Итого задолженности в бюджет:</td>
      <td><?= number_format($financeData['totalTaxArrear'], 2, '.', ' ') ?></td>
    </tr>
    <tr>
      <td>Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам:</td>
      <td><?= number_format($financeData['pensionContributionArrear'], 2, '.', ' ') ?></td>
    </tr>
    <tr>
      <td>Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование:</td>
      <td><?= number_format($financeData['socialContributionArrear'], 2, '.', ' ') ?></td>
    </tr>    
    <tr>
      <td>Задолженность по социальным отчислениям:</td>
      <td><?= number_format($financeData['socialHealthInsuranceArrear'], 2, '.', ' ') ?></td>
    </tr>    
</table>

<br>
<h5 class="header5">Таблица задолженностей по органам государственных доходов</h5>

<? foreach($financeData['taxOrgInfo'] as $taxOrgInfo) : ?>

<div class="iblock">

<h4 class="header4">Орган государственных доходов <?= $taxOrgInfo['nameRu'] ?> Код ОГД <?= $taxOrgInfo['charCode'] ?></h4>
<br>
<div class="line">По состоянию на <b><?= date('Y-m-d', floor($taxOrgInfo['reportAcrualDate'] / 1000)) ?></b></div>
<div class="line">Всего задолженности: <b><?= number_format($taxOrgInfo['totalArrear'], 2, '.', ' ') ?></b></div>
<br>

<table class="info2">
    <tr>
      <td>Итого задолженности в бюджет:</td>
      <td><?= number_format($taxOrgInfo['totalTaxArrear'], 2, '.', ' ') ?></td>
    </tr>
    <tr>
      <td>Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам:</td>
      <td><?= number_format($taxOrgInfo['pensionContributionArrear'], 2, '.', ' ') ?></td>
    </tr>
    <tr>
      <td>Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование:</td>
      <td><?= number_format($taxOrgInfo['socialContributionArrear'], 2, '.', ' ') ?></td>
    </tr>    
    <tr>
      <td>Задолженность по социальным отчислениям:</td>
      <td><?= number_format($taxOrgInfo['socialHealthInsuranceArrear'], 2, '.', ' ') ?></td>
    </tr>    
</table>

<br>
<h5 class="header5">Таблица задолженностей по налогоплательщику и его структурным подразделениям</h5>

<? foreach($taxOrgInfo['taxPayerInfo'] as $taxPayerInfo) : ?>

<table class="info1">
    <tr>
      <td>Наименование налогоплательщика:</td>
      <td><?= $taxPayerInfo['nameRu'] ?></td>
    </tr>
    <tr>
      <td>ИИН/БИН налогоплательщика:</td>
      <td><?= $taxPayerInfo['iinBin'] ?></td>
    </tr>
    <tr>
      <td>Всего задолженности (тенге):</td>
      <td><?= number_format($taxPayerInfo['totalArrear'], 2, '.', ' ') ?></td>
    </tr>    
</table>

<br>
<table class="info3">
  <thead>
    <tr>
      <th>КБК</th>
      <th>Задолженность по платежам, учет по которым ведется в органах государственных доходов</th>
      <th>Задолженность по сумме пени</th>
      <th>Задолженность по сумме процентов</th>
      <th>Задолженность по сумме штрафа</th>
      <th>Всего задолженности</th>
    </tr>
   </thead>
   <tbody>
   
<? foreach($taxPayerInfo['bccArrearsInfo'] as $bccArrearsInfo) : ?>
   
    <tr>
      <td><?= $bccArrearsInfo['bcc'] ?> <?=$bccArrearsInfo['bccNameRu'] ?></td>
      <td><?= number_format($bccArrearsInfo['taxArrear'], 2, '.', ' ') ?></td>
      <td><?= number_format($bccArrearsInfo['poenaArrear'], 2, '.', ' ') ?></td>
      <td><?= number_format($bccArrearsInfo['percentArrear'], 2, '.', ' ') ?></td>
      <td><?= number_format($bccArrearsInfo['fineArrear'], 2, '.', ' ') ?></td>
      <td><?= number_format($bccArrearsInfo['totalArrear'], 2, '.', ' ') ?></td>
    </tr>

<? endforeach; ?>
    
  </tbody>
</table>

<? endforeach; ?>

</div>

<? endforeach; ?>

<div class="form-group">

   <?= Html::hiddenInput('data', json_encode($financeData), ['id' => 'data']); ?>
   <?= Html::button('Сохранить', ['class' => 'btn btn-primary', 'style' => 'width:200px', 'id' => 'saveButton']) ?>
</div>
<?php
$script = <<< JS
    $(document).ready(function() {        
    });
    
    $( "#saveButton" ).click(function() {
        $.post({
          type: "POST",
          url: "/index.php?r=site%2Fsave",
          data: $( "#data" ),         
          dataType: "json"
        })
        .done(function(msg) { var msgArr=jQuery.parseJSON(msg); alert(msgArr.message); })
        .fail(function() { alert("Ошибка сохранения данных!"); });
    });

JS;
$this->registerJs($script);
?>






