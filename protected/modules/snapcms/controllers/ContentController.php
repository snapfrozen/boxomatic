<?php

class ContentController extends DefaultController
{
	public static $menuArray=array(
		//array('label'=>'List Content', 'url'=>array('index')),
		array('label'=>'Manage Content', 'url'=>array('/snapcms/content/admin')),
		//array('label'=>'Menus', 'url'=>array('/snapcms/menu/admin')),
	);
	
	public function beforeRender($view)
	{
		$this->menu=self::$menuArray;
		return parent::beforeRender($view);
	}
	
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
			array('allow',
				'actions'=>array('index','view'),
				'roles'=>array('View Content'),
			),
			array('allow', 
				'actions'=>array('create'),
				'roles'=>array('Create Content'),
			),
			array('allow',
				'actions'=>array('update','admin'),
				'roles'=>array('Update Content'),
			),
			array('allow',
				'actions'=>array('delete'),
				'roles'=>array('Delete Content'),
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
	public function actionCreate($type)
	{
		$model=new Content;
		$model->setType($type);
		$model->published=true;

		if(isset($_POST['Content']))
		{
			$model->attributes=$_POST['Content'];
			$ContentType = $model->ContentType;
			$ContentType->attributes=$_POST['ContentType'];
			
			$contentSaved = $model->save();
			$ContentType->save(); //Have to assume saved because function always returns 0;
			$menuItemsSaved = true;
			
			if(isset($_POST['MenuItem']))
			{
				foreach($_POST['MenuItem'] as $data) 
				{
					if(isset($data['include'])) 
					{
						$MenuItem = $this->_populateMenuItem(new MenuItem, $data, $model);
						if(!$MenuItem->save())
							$menuItemsSaved = false;
					}
				}
			}
			
			if($contentSaved && $menuItemsSaved) 
			{
				Yii::app()->user->setFlash('success','Content Created');
				$this->redirect(array('content/admin','id'=>$model->id));
			}
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
		$contentSaved = false;
		$menuItemsSaved = false;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Content']))
		{
			$model->attributes=$_POST['Content'];
			$contentSaved = $model->save();
			
			$menuItemsSaved = true;
			
			if(isset($_POST['MenuItem']))
			{
				foreach($_POST['MenuItem'] as $data) 
				{
					if(isset($data['include'])) 
					{
						if(!empty($data['id'])) {
							$MenuItem = MenuItem::model()->findByPk($data['id']);
						} else {
							$MenuItem = new MenuItem;
						}
						$MenuItem = $this->_populateMenuItem($MenuItem, $data, $model);
						if(!$MenuItem->save())
							$menuItemsSaved = false;
					}
					else 
					{						
						if(!empty($data['id'])) {
							MenuItem::model()->findByPk($data['id'])->delete();
						}
					}
				}
			}
		}
		
		if(isset($_POST['ContentType']))
		{
			$ContentType = $model->ContentType;
			$ContentType->attributes=$_POST['ContentType'];

			foreach($ContentType->fileFields as $field)
			{
				if(isset($_POST[$field.'_delete'])) {
					$ContentType->$field = null;
				}
			}
			
			$ContentType->save(); //Have to assume saved because function always returns 0;
			
			if(Yii::app()->request->isAjaxRequest)
			{
				echo CJSON::encode($ContentType->errors);
				Yii::app()->end();
			}
		}
		
		if($contentSaved && $menuItemsSaved) 
		{
			Yii::app()->user->setFlash('success','Content Updated');
			//$this->redirect(array('content/admin','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	private function _populateMenuItem($MenuItem, $data, $Content)
	{
		$MenuItem->attributes = $data;
		$MenuItem->content_id = $Content->id;
		
		if(empty($MenuItem->title))
			$MenuItem->title = $Content->title;
		if(empty($MenuItem->path))
			$MenuItem->path = '/content/'.SnapFormat::slugify($MenuItem->title);
		
		return $MenuItem;
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
		$dataProvider=new CActiveDataProvider('Content');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Content('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Content']))
			$model->attributes=$_GET['Content'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Content the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Content::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Content $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='content-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
