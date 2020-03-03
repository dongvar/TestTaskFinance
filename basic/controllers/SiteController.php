<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\FinanceInfo;
use app\models\TaxOrgInfo;
use app\models\TaxPayerInfo;
use app\models\BccArrearsInfo;
use app\models\RequestForm;
use app\models\DataSource;

class SiteController extends Controller
{
    /*
    *
    */    
    public function actionIndex()
    {   
        $requestForm = new RequestForm();
        
        // Проверяем корректно ли введен ИИН (12 символов)
        if ($requestForm->load(Yii::$app->request->post()) && $requestForm->validate()) {
        
            // Создаем источник данных, через него получаем всю неоюходимую информацию с сайта 
            $dataSource = new DataSource(
                  Yii::$app->params['antiCaptchaKey'], 
                  Yii::$app->params['webSiteCaptchaKey'],
                  Yii::$app->params['webSiteCaptchaURL'],
                  Yii::$app->params['financeInfoURL']);         
        
            // Загружаем данные с сайта источника с обходом капчи по ИИН
            $financeData = $dataSource->getData($requestForm->iinBin);   
            
            //Это для тестирования : НУЖНО УДАЛИТЬ!
            //$financeData = json_decode('{"nameRu":"ШЕПЕЛЕВ АЛЕКСАНДР ПЕТРОВИЧ","nameKk":"ШЕПЕЛЕВ АЛЕКСАНДР ПЕТРОВИЧ","iinBin":"791005350297","totalArrear":10299.84,"totalTaxArrear":10299.84,"pensionContributionArrear":0,"socialContributionArrear":0,"socialHealthInsuranceArrear":0,"appealledAmount":null,"modifiedTermsAmount":null,"rehabilitaionProcedureAmount":null,"sendTime":1582994336000,"taxOrgInfo":[{"nameRu":"Республиканское государственное учреждение “Управление государственных доходов по Алматинскому району Департамента государственных доходов по городу Астане Комитета государственных доходов Министерства финансов Республики Казахстан”","nameKk":"«Қазақстан Республикасы Қаржы министрлігінің Мемлекеттік кірістер комитеті Астана қаласы бойынша Мемлекеттік кірістер департаментінің Алматы ауданы бойынша Мемлекеттік кірістер басқармасы» республикалық мемлекеттік мекемесі","charCode":"620201","reportAcrualDate":1582912800000,"totalArrear":10299.84,"totalTaxArrear":10299.84,"pensionContributionArrear":0,"socialContributionArrear":0,"socialHealthInsuranceArrear":0,"appealledAmount":null,"modifiedTermsAmount":null,"rehabilitaionProcedureAmount":null,"taxPayerInfo":[{"nameRu":"ШЕПЕЛЕВ АЛЕКСАНДР ПЕТРОВИЧ","nameKk":"ШЕПЕЛЕВ АЛЕКСАНДР ПЕТРОВИЧ","iinBin":"791005350297","totalArrear":10299.84,"bccArrearsInfo":[{"bcc":"104402","bccNameRu":"Hалог на транспортные средства с физических лиц","bccNameKz":"Жеке тұлғалардың көлiк құралдарына салынатын салық","taxArrear":9035,"poenaArrear":1264.84,"percentArrear":0,"fineArrear":0,"totalArrear":10299.84}]}]}]}', true);            
            
            if(!$financeData)
                return $this->render('error', ['message' => $dataSource->getErrorMessage()]);
                
            return $this->render('request-confirm', ['financeData' => $financeData]);
        } else {            
            return $this->render('index', ['requestForm' => $requestForm]);
        }  
    }
    
    /*
    *
    */ 
    public function actionSave()
    {   
        $financeData = json_decode(Yii::$app->request->post('data'), true);
        
        // Начинаем транзакцию, чтобы сохранить целостность данных
        $transaction = Yii::$app->db->beginTransaction();
        
        // Созаем модель FinanceInfo и заполняем данными получеными с сайта источника
        $financeInfo = new FinanceInfo();  
        $financeInfo->initAttributes($financeData);
        
        $commit = true;
        
        // Проверяем есть ли уже в базе данные с таким ИИН, если есть удаляем для последующего обновления                    
        if($financeInfo->findOne(['iin_bin' => $financeData['iinBin']])) {
            if(!$this->deleteFinanceInfo($financeData['iinBin']))
                $commit = false;
        }
        
        // Далее сохраняем все полученые данные с сайта источника в базу (в четыре таблицы)
        
        if(!$financeInfo->save())
            $commit = false;
                        
        foreach($financeData['taxOrgInfo'] as $taxOrgData) {
        
            // Созаем модель TaxOrgInfo и заполняем данными получеными с сайта источника     
            $taxOrgInfo = new TaxOrgInfo();        
            $taxOrgInfo->initAttributes($taxOrgData, $financeData['iinBin']);              
            if(!$taxOrgInfo->save()) {
                $commit = false;
                break 1;
            }
                
            foreach($taxOrgData['taxPayerInfo'] as $taxPayerData) {
            
                // Созаем модель TaxPayerInfo и заполняем данными получеными с сайта источника                
                $taxPayerInfo = new TaxPayerInfo();    
                $taxPayerInfo->initAttributes($taxPayerData, $taxOrgInfo->id);                   
                if(!$taxPayerInfo->save()) {
                    $commit = false;
                    break 2;
                }
                 
                // Созаем модель BccArrearsInfo и заполняем данными получеными с сайта источника   
                foreach($taxPayerData['bccArrearsInfo'] as $bccArrearsData) {                                
                    $bccArrearsInfo = new BccArrearsInfo(); 
                    $bccArrearsInfo->initAttributes($bccArrearsData, $taxPayerInfo->id);   
                    if(!$bccArrearsInfo->save()) {
                        $commit = false;
                        break 3;
                    }
                }                
            }
        }
        
        // Проверяем не было ли сбоев при сохранении данных в базу.
        // В зависимости от результата делаем ролбек или коммит, и отправляем сообщение в браузер
        if($commit) {
                $transaction->commit();
                return json_encode('{"message":"Запись с ИИН '.$financeData['iinBin'].' успешно добавлена в базу данных!"}', JSON_UNESCAPED_UNICODE);                      
            } else {
                $transaction->rollBack();
                return;
        }
    }
        
    /*
    * Удалят все данные связаные с определенным ИИН
    */ 
    public function deleteFinanceInfo($iinBin)
    {
        $financeInfo = new FinanceInfo();   
        $taxOrgInfoModel = new TaxOrgInfo();
        $taxPayerInfoModel = new TaxPayerInfo();    
        $bccArrearsInfoModel = new BccArrearsInfo();
                
        $success = true;
        foreach($taxOrgInfoModel->find()->where(['iin_bin' => $iinBin])->all() as $taxOrgInfo)
        {
            foreach($taxPayerInfoModel->find()->where(['tax_org_info_id' => $taxOrgInfo->id])->all() as $taxPayerInfo)
            {
                foreach($bccArrearsInfoModel->find()->where(['tax_payer_info_id' => $taxPayerInfo->id])->all() as $bccArrearsInfo)
                {
                    if(!$bccArrearsInfo->delete()) {                                     
                        $success = false;
                        break 3;
                    }                                            
                }
                if(!$taxPayerInfo->delete()) {                                            
                    $success = false;
                    break 2;
                }
            }            
            if(!$taxOrgInfo->delete()) {                
                $success = false;
                break 1;
            }            
        }
        
        if($success && !$financeInfo->deleteAll(['iin_bin' => $iinBin]))
            $success = false;
        
        return $success;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}
