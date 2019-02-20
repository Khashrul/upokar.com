<?php

class ExpertDetailsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ExpertDetails;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ExpertDetails']))
		{
			$model->attributes=$_POST['ExpertDetails'];
			$imageInstance = Generic::validateUploadImage($model,'expert_image');
			if($imageInstance){
				$imageName = time() + 1;

				if($imageSaveName = Generic::uploadImage($imageInstance, $imageName,'expert_image', 110, 110)){

					if($imageSaveName == 'Invalid image type'){
						Yii::app()->user->setFlash('error', 'Invalid image type! Allowed image types are jpg, jpeg, png & gif.');
						$this->render('create',array('model'=>$model,));
						Yii::app()->end();
					}

					$model->expert_image = $imageSaveName;
				}
			}
			$name_string = $_POST['ExpertDetails']['expert_name'];
			$regex = strtoupper(strtr($name_string, array('.' => '', ',' => '',' '=>'')));
			$code_name = substr($regex,0,3).'-'.rand(1234,4) ;
			$model->expert_code_name = $code_name;
			$model->current_status = 1;
			$model->create_date = date('y-m-d');
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['ExpertDetails']))
		{
			$old_image_name = $model->expert_image;
			$model->attributes=$_POST['ExpertDetails'];
			$imageInstance = Generic::validateUploadImage($model,'expert_image');

			if($imageInstance){
				$imageName = time() + 1;
				if($imageSaveName = Generic::uploadImage($imageInstance, $imageName,'expert_image', 110, 110)){
					if($imageSaveName == 'Invalid image type'){
						$model->expert_image = $old_image_name;
						Yii::app()->user->setFlash('error', 'Invalid image type! Allowed image types are jpg, jpeg, png & gif.');
						$this->render('update',array('model'=>$model,));
						Yii::app()->end();
					}
					$model->expert_image = $imageSaveName;
					//Need to delete old image
					Generic::deleteThumb($old_image_name, 'expert_image');
				}
			}
			if(!trim($model->expert_image)){
				$model->expert_image = $old_image_name;
			}
			if($model->validate() && $model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ExpertDetails');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ExpertDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpertDetails']))
			$model->attributes=$_GET['ExpertDetails'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ExpertDetails the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ExpertDetails::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ExpertDetails $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='expert-details-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}











}
