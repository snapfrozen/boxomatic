<?php

class GrowerController extends Controller
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
				'actions'=>array('index','view','GeoCode'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','map','search'),
				'roles'=>array('grower'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('admin'),
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
		$model=new Grower;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Grower']))
		{
			$model->attributes=$_POST['Grower'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->grower_id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Grower']))
		{
			$model->attributes=$_POST['Grower'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->grower_id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Grower');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Shows the grower map
	 */
	public function actionMap()
	{
		$model=new Grower('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Grower']))
			$model->attributes=$_GET['Grower'];

		$criteria=new CDbCriteria();
		$criteria->select='grower_id,grower_name,lattitude,longitude';
		
//		$Growers=$model->findAll($criteria);
		$Growers=$model->search(false)->getData();
		
		//Build grower array and grower items in a format suitable for Mustache js
		$growerArray=SnapUtil::makeArray($Growers);
		foreach($Growers as $Grower) {
			$growerItems=SnapUtil::makeArray($Grower->GrowerItems);
			foreach($growerItems as $gi) {
				$growerArray[$Grower->grower_id]['GrowerItems'][]=$gi;
			}
			$growerArray[$Grower->grower_id]['has_items']=!empty($growerArray[$Grower->grower_id]['GrowerItems']) ? true : false;
		}

		$this->layout='//layouts/column1';
		$this->render('map',array(
			'model'=>$model,
			'Growers'=>$growerArray,
		));
	}
	
	public function actionSearch()
	{
		$model=new Grower('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Grower']))
			$model->attributes=$_GET['Grower'];

		$criteria=new CDbCriteria();
		$criteria->select='grower_id,grower_name,lattitude,longitude';

		$Growers=$model->search(false)->getData();
		
		//Build grower array and grower items in a format suitable for Mustache js
		$growerArray=SnapUtil::makeArray($Growers);
		foreach($Growers as $Grower) {
			$growerItems=SnapUtil::makeArray($Grower->GrowerItems);
			foreach($growerItems as $gi) {
				$growerArray[$Grower->grower_id]['GrowerItems'][]=$gi;
			}
			$growerArray[$Grower->grower_id]['has_items']=!empty($growerArray[$Grower->grower_id]['GrowerItems']) ? true : false;
		}
		
		echo json_encode($growerArray);
		
		exit;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Grower('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Grower']))
			$model->attributes=$_GET['Grower'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Grower::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='grower-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionGeoCode() 
	{		
		define("MAPS_HOST", "maps.google.com");
		//define("KEY", "AIzaSyAU7aJq2EcQYJV7BsjZg1lkhR2dYBTZxfU");
		define("KEY", "AIzaSyDtX7h4kdxUSrWpqVz3uRYP2-gxDMXa6hM");
		
		$delay = 0;
		$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
		
		$Growers=Grower::model()->findAll();
		
		foreach($Growers as $grower)
		{ 
			$id = $grower->grower_id;
			$address = $grower->grower_address . ' ' . $grower->grower_address2 . ', ' . $grower->grower_suburb . ', ' . $grower->grower_state . ', ' . $grower->grower_postcode;
			$address = trim($address, ' ,');
		
			if(!empty($address))
			{
				$request_url = $base_url . "&q=" . urlencode($address . ', Australia');
				$xml = simplexml_load_file($request_url) or die("url not loading");
				$status = $xml->Response->Status->code;
				
				if (strcmp($status, "200") == 0) 
				{
					// Successful geocode
					$geocode_pending = false;
					$coordinates = $xml->Response->Placemark->Point->coordinates;
					$coordinatesSplit = split(",", $coordinates);
					// Format: Longitude, Latitude, Altitude
					$lat = $coordinatesSplit[1];
					$lng = $coordinatesSplit[0];
					
					$grower->lattitude = $lat;
					$grower->longitude = $lng;
					$grower->save();
		
				} 
				else if (strcmp($status, "620") == 0) 
				{
					// sent geocodes too fast
					$delay += 100000;
				} 
				else 
				{
					// failure to geocode
					$geocode_pending = false;
					echo "Address " . $address . ", Australia failed to geocoded. ";
					echo "Received status " . $status . "\n";
				}
				usleep($delay);
			}
		}

	}
}
