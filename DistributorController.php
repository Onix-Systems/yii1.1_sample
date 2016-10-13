<?php

/**
 * Controller for distributors managing
 */
class DistributorController extends ClientController
{
    protected function beforeAction($action)
    {
        $this->layout = 'admin';
        if (!Yii::app()->user->checkPermission($this->id . '/' . $action->id)) {
            throw new CHttpException(403);
        }
        return parent::beforeAction($action);
    }

    /**
     * Show distributor
     */
    public function actionIndex()
    {
        $model = new Distributor();
        if (Yii::app()->request->getParam('ajax') && Yii::app()->request->getParam('Distributor')) {
            $model->attributes = Yii::app()->request->getParam('Distributor');
        }
        $this->render('index', array('model' => $model));
    }

    /**
     * @label Creating distributor
     */
    public function actionCreate()
    {
        $client = Yii::app()->getRequest()->getPost('Client');
        $distributor = Yii::app()->getRequest()->getPost('Distributor');
        if ($client && $distributor) {
            $DistributorPostInfo = array_merge($client, $distributor);
            $model = new Distributor();
            $model->typeClient = Client::TYPE_CLIENT_DISTRIBUTOR;
            $model->clientGlnInfo = $this->createClientGlnInfoArray($distributor, $model->clientGln);
            $model->attributes = $DistributorPostInfo;
            if (isset($distributor['clientContactInfo'])) {
                $model->clientContactInfo = $distributor['clientContactInfo'];
            } else {
                $model->clientContactInfo = array();
            }
            if (isset($distributor['manufacturersIds'])) {
                $model->manufacturersIds = $distributor['manufacturersIds'];
            } else {
                $model->manufacturersIds = array();
            }
            $this->handleForm($model);
        } else {
            $model = new Distributor();
        }
        $this->render('create', array('model' => $model));
    }

    /**
     * @label Change distributor information
     * @param $id
     */
    public function actionUpdate($id)
    {
        $model = Distributor::model()->with(array('manufacturers'))->findByPk($id);
        $model->clientContactInfoSaveOld();
        $distributor = Yii::app()->getRequest()->getPost('Distributor');
        $model->clientGlnInfo = $this->createClientGlnInfoArray($distributor, $model->clientGln);
        $modelClientContract = new ClientUcatContract();
        if ($distributor) {
            unset($distributor['gln']);
            $model->attributes = $distributor;
            if (isset($distributor['clientContactInfo'])) {
                $model->clientContactInfo = $distributor['clientContactInfo'];
            } else {
                $model->clientContactInfo = array();
            }
            if (isset($distributor['manufacturersIds'])) {
                $model->manufacturersIds = $distributor['manufacturersIds'];
            } else {
                $model->manufacturersIds = array();
            }
            $this->handleForm($model);
        }
        $this->render('update', array(
                'model' => $model,
                'modelClientContract' => $modelClientContract
            )
        );
    }
}