<?php

namespace app\controllers;

use Yii;
use app\models\FinanceInfo;
use app\models\FinanceInfoSearch;
use app\models\TaxOrgInfo;
use app\models\TaxPayerInfo;
use app\models\BccArrearsInfo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FinanceInfoController implements the CRUD actions for FinanceInfo model.
 */
class FinanceInfoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all FinanceInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FinanceInfo model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $taxOrgInfo = new TaxOrgInfo();
        $taxPayerInfo = new TaxPayerInfo();    
        $bccArrearsInfo = new BccArrearsInfo();
                
        
        return $this->render('view', [
            'financeInfoModel' => $this->findModel($id),            
            'taxOrgInfoModel' => $taxOrgInfo,
            'taxPayerInfoModel' => $taxPayerInfo,
            'bccArrearsInfoModel' => $bccArrearsInfo,
        ]);
    }

    /**
     * Deletes an existing FinanceInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $taxOrgInfoModel = new TaxOrgInfo();
        $taxPayerInfoModel = new TaxPayerInfo();    
        $bccArrearsInfoModel = new BccArrearsInfo();
        
        $transaction = Yii::$app->db->beginTransaction();
        
        $commit = true;
        foreach($taxOrgInfoModel->find()->where(['iin_bin' => $id])->all() as $taxOrgInfo)
        {
            foreach($taxPayerInfoModel->find()->where(['tax_org_info_id' => $taxOrgInfo->id])->all() as $taxPayerInfo)
            {
                foreach($bccArrearsInfoModel->find()->where(['tax_payer_info_id' => $taxPayerInfo->id])->all() as $bccArrearsInfo)
                {
                    if(!$bccArrearsInfo->delete()) {                                     
                        $commit = false;
                        break 3;
                    }                                            
                }
                if(!$taxPayerInfo->delete()) {                                            
                    $commit = false;
                    break 2;
                }
            }            
            if(!$taxOrgInfo->delete()) {                
                $commit = false;
                break 1;
            }            
        }
        
        if($commit && !$this->findModel($id)->delete())
            $commit = false;
        
        
        if($commit)
            $transaction->commit();
        else
            $transaction->rollBack();
            

        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FinanceInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошеная страница не существует!.');
    }
}
