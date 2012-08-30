<?php

class BoxItemController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
				'actions'=>array('index','view'),
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update'),
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
	public function actionCreate($week=null,$item=null,$grower=null)
	{	
		$model=new BoxItem;
		$SelectedWeek=null;
		$NewItem=null;
		
		if(isset($_POST['bc']))
		{
			foreach($_POST['bc'] as $boxContents)
			{
				foreach($boxContents['BoxItem'] as $boxItem)
				{	
					if(isset($boxItem['box_item_id']))
						$BoxItem=BoxItem::model()->findByPk($boxItem['box_item_id']);
					else 
						$BoxItem=new BoxItem;
					
					//Delete if boxItem exists and quantity has been set to 0
					if($BoxItem && isset($boxItem['box_item_id']) && $boxItem['item_quantity']==0) 
					{
						$BoxItem->delete();
					}
					
					if($boxItem['item_quantity']>0) 
					{
						$BoxItem->item_name=$boxContents['item_name'];
						$BoxItem->grower_id=$boxContents['grower_id'];
						$BoxItem->item_unit=$boxContents['item_unit'];
						$BoxItem->item_value=$boxContents['item_value'];
						$BoxItem->item_quantity=$boxItem['item_quantity'];
						$BoxItem->box_id=$boxItem['box_id'];
						$BoxItem->save();
					}
				}
			}
		}
		
		$GrowerItems=new GrowerItem('search');
		$GrowerItems->unsetAttributes();  // clear any default values
		if(isset($_GET['GrowerItem']))
			$GrowerItems->attributes=$_GET['GrowerItem'];
		
		$Weeks=Week::model()->findAll();
		//$Weeks=Week::model()->findAll('week_delivery_date > NOW()');

		//Get the boxes for the selected week
		$WeekBoxes=null;
		if($week) {
			$WeekBoxes=Box::model()->findAll(array(
				'condition'=>'week_id=:weekId',
				'params'=>array('weekId'=>$week),
				'order'=>'size_id',
			));
			$SelectedWeek=Week::model()->findByPk($week);
		}
		
		//Item has been selected from inventory, if it doesn't exist in the week
		//Load it to be added as a new row up the top of the box item list
		$selectedItemId=null;
		if($item) 
		{
			$NewItem=GrowerItem::model()->findByPk($item);
			$TmpItem=BoxItem::model()->with('Box')->find(
				'item_name=:itemName AND 
				grower_id=:growerId AND 
				item_unit=:itemUnit AND 
				item_value=:itemValue AND 
				Box.week_id=:weekId',
				array (
					':itemName'=>$NewItem->item_name,
					':growerId'=>$NewItem->grower_id,
					':itemUnit'=>$NewItem->item_unit,
					':itemValue'=>$NewItem->item_value,
					':weekId'=>$week,
				)
			);
			
			if($TmpItem) 
			{
				$selectedItemId=$TmpItem->box_item_id;
			}
			else 
			{
				foreach($WeekBoxes as $WeekBox)
				{
					$BoxItem=new BoxItem;
					$BoxItem->item_name=$NewItem->item_name;
					$BoxItem->grower_id=$NewItem->grower_id;
					$BoxItem->item_unit=$NewItem->item_unit;
					$BoxItem->item_value=$NewItem->item_value;
					$BoxItem->item_quantity=1;
					$BoxItem->box_id=$WeekBox->box_id;
					$BoxItem->save();
					
					$selectedItemId=$BoxItem->box_item_id;
				}
			}
		}
		
		//User chose to add a new entry by clicking a grower name
		if($grower)
		{
			$TmpItem=BoxItem::model()->with('Box')->find(
				'item_name=:itemName AND 
				grower_id=:growerId AND 
				item_unit=:itemUnit AND 
				item_value=:itemValue AND 
				Box.week_id=:weekId',
				array (
					':itemName'=>'',
					':growerId'=>(int)$grower,
					':itemUnit'=>'KG',
					':itemValue'=>0,
					':weekId'=>$week,
				)
			);
			
			if($TmpItem) 
			{
				$selectedItemId=$TmpItem->box_item_id;
			}
			else 
			{
				foreach($WeekBoxes as $WeekBox)
				{
					$BoxItem=new BoxItem;
					$BoxItem->item_name='';
					$BoxItem->grower_id=(int)$grower;
					$BoxItem->item_unit='KG';
					$BoxItem->item_value=0;
					$BoxItem->item_quantity=1;
					$BoxItem->box_id=$WeekBox->box_id;
					$BoxItem->save();

					$selectedItemId=$BoxItem->box_item_id;
				}
			}
		}
		
		$this->performAjaxValidation($model);
		if(isset($_POST['BoxItem']))
		{
			if(!empty($_POST['BoxItem']['box_item_id']))
				$model=$this->loadModel($_POST['BoxItem']['box_item_id']);
			
			$model->attributes=$_POST['BoxItem'];
			$model->save();				
		}
		
		$this->render('create',array(
			'model'=>$model,
			'GrowerItems'=>$GrowerItems,
			'Weeks'=>$Weeks,
			'WeekBoxes'=>$WeekBoxes,
			'SelectedWeek'=>$SelectedWeek,
			'selectedItemId'=>$selectedItemId,
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

		if(isset($_POST['BoxItem']))
		{
			$model->attributes=$_POST['BoxItem'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->box_item_id));
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
		$dataProvider=new CActiveDataProvider('BoxItem');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BoxItem('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BoxItem']))
			$model->attributes=$_GET['BoxItem'];

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
		$model=BoxItem::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='box-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
