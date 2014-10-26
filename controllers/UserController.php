<?php

class UserController extends BoxomaticController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('passwordReset', 'forgottenPassword', 'captcha'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'view', 'loginAs', 'changePassword', 'dontWant', 'orders', 'payments', 'makePayment','pastOrders'),
                'roles' => array('customer', 'Admin'),
            //'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'admin', 'delete', 'create', 'customers', 'resetPassword', 'export'),
                'roles' => array('Admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        if (!Yii::app()->user->checkAccess('Admin') && $model->id != Yii::app()->user->id)
        {
            throw new CHttpException(403, 'Access Denied.');
        }
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionPayments()
    {
        $id = Yii::app()->user->id;
        $dataProvider = new CActiveDataProvider('UserPayment', array(
            'criteria' => array(
                'condition' => 'user_id=:userId',
                'order' => 'payment_date DESC',
                'params' => array(
                    ':userId' => $id,
                )
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
        $this->render('payments', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    /**
     * Page for customers to view their past orders.
     */
    public function actionPastOrders()
    {
        $userId = Yii::app()->user->id;
        $User = BoxomaticUser::model()->findByPk($userId);
        $Orders = Order::getPastOrders($userId);
        
        $this->render('past_orders', array(
            'Customer' => $User,
            'Orders' => $Orders,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionMakePayment()
    {
        $model = new UserPayment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserPayment']))
        {
            $model->attributes = $_POST['UserPayment'];
            $model->user_id = Yii::app()->user->user_id;
            $model->payment_date = new CDbExpression('NOW()');

            if ($model->save())
            {
                $Customer = $model;
                $validator = new CEmailValidator();
                if ($validator->validateValue($Customer->email))
                {
                    //email payment receipt
                    $adminEmail = SnapUtil::config('boxomatic/adminEmail');
                    $adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
                    $message = new YiiMailMessage('Payment receipt');
                    $message->view = 'customer_payment_receipt';
                    $message->setBody(array('Customer' => $Customer, 'UserPayment' => $model), 'text/html');
                    $message->addTo($Customer->email);
                    $message->addTo($adminEmail);
                    $message->setFrom(array($adminEmail => $adminEmailFromName));

                    if (!@Yii::app()->mail->send($message))
                    {
                        $mailError = true;
                    }
                }
                $this->redirect(array('view', 'id' => $model->payment_id));
            }
        }

        $User = BoxomaticUser::model()->findByPk(Yii::app()->user->id);
        $this->render('make_payment', array(
            'model' => $model,
            'User' => $User,
            'Customer' => $User,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $Customer = $this->loadModel($id);
        
        if($Customer->id !== Yii::app()->user->id) {
            throw new CHttpException(403,'You are not authorized to perform this action.');
        }

        if (isset($_POST['role']) && $_POST['role'] == 'customer')
        {
            $UserLoc = new UserLocation;
            $UserLoc->user_id = $Customer->user_id;
            $UserLoc->location_id = $Customer->location_id;
            $UserLoc->address = $Customer->user_address;
            $UserLoc->address2 = $Customer->user_address2;
            $UserLoc->suburb = $Customer->user_suburb;
            $UserLoc->state = $Customer->user_state;
            $UserLoc->postcode = $Customer->user_postcode;
            $UserLoc->phone = !empty($Customer->user_phone) ? $Customer->user_phone : $Customer->user_mobile;
            $UserLoc->save();

            $Customer->user_id = $Customer->user_id;
            $Customer->update(array('user_id'));
        }

        $allSaved = true;
        if (isset($_POST['Supplier']))
        {
            $Supplier = $Customer->Supplier;
            $Supplier->attributes = $_POST['Supplier'];
            if (!$Supplier->update())
                $allSaved = false;
        }

        if (isset($_POST['role']))
        {
            $Customer->setRole($_POST['role']);
        }

        if (isset($_POST['BoxomaticUser']))
        {
            $oldLocation = $Customer->location_id;
            $oldDeliveryDay = $Customer->delivery_day;
            
            $Customer->attributes = $_POST['BoxomaticUser'];

            $locationId = $_POST['BoxomaticUser']['delivery_location_key'];
            $custLocationId = new CDbExpression('NULL');
            if (strpos($locationId, '-'))
            { //has a customer location
                $parts = explode('-', $locationId);
                $locationId = $parts[1];
                $custLocationId = $parts[0];
            }
            $Customer->location_id = $locationId;
            $Customer->user_location_id = $custLocationId;
            
            $Customer->validate();
            if (!$Customer->update())
                $allSaved = false;
            
            //Update the cart to prevent ordering on an unavailable day
            $BoxoCart = new BoxoCart;
            $BoxoCart->delivery_day = $Customer->delivery_day;
            $BoxoCart->setLocation_id($Customer->location_id);
            $BoxoCart->setDelivery_date_id($BoxoCart->getNextDeliveryDate()->id);
            
            //The frontend system currently doesn't handle ordering from multiple locations
            //so delete all orders if changing location
            if($Customer->location_id != $oldLocation || $Customer->delivery_day != $oldDeliveryDay) 
            {
                $deleted = false;
                foreach($Customer->getFutureOrders() as $Order) {
                    $Order->delete();
                    $deleted = true;
                }
                $BoxoCart->emptyCart();
                if($deleted) {
                    Yii::app()->user->setFlash('warning', 'All future orders removed.');
                }
            }

            if ($allSaved)
                $this->redirect(array('user/update', 'id' => $Customer->id));
        }

        $custLocDataProvider = null;
        $custLocDataProvider = new CActiveDataProvider('UserLocation', array(
            'criteria' => array(
                'condition' => 'user_id=' . $Customer->id
            )
        ));

        $this->render('update', array(
            'model' => $Customer,
            'custLocDataProvider' => $custLocDataProvider
        ));
    }

    /**
     * Change password page
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangePassword($id)
    {
        $model = $this->loadModel($id);
        $model->scenario = 'changePassword';
        if (isset($_POST['BoxomaticUser']))
        {
            $model->attributes = $_POST['BoxomaticUser'];
            if ($model->validate())
            {
                $model->update();
                Yii::app()->user->setFlash('success', "Password updated.");
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('changePassword', array(
            'model' => $model,
        ));
    }

    /**
     *  Reset password action performed by admin
     */
    public function actionResetPassword($id)
    {
        $User = $this->loadModel($id);
        if ($User->resetPasswordAndSendWelcomeEmail())
            Yii::app()->user->setFlash('success', "Password changed and email sent");
        else
            Yii::app()->user->setFlash('error', "Password changed but no email sent");

        $this->redirect(array('user/customers'));
    }

    public function actionOrders($id, $fromToday = true)
    {
        $User = $this->loadModel($id);

        $c = new CDbCriteria;
        $c->with = 'DeliveryDate';
        $c->addCondition('user_id=:userId');
        if ($fromToday == true)
        {
            $c->addCondition('DeliveryDate.date >= NOW()');
        }
        $c->order = 'DeliveryDate.date ASC';
        $c->params = array(
            ':userId' => $id,
        );

        $ordersDP = new CActiveDataProvider('Order', array(
            'criteria' => $c
        ));

        $this->render('orders', array(
            'User' => $User,
            'ordersDP' => $ordersDP,
        ));
    }

    /**
     * @param integer $id the ID of the model to be displayed
     */
    public function actionDontWant($id, $cat = null, $product = null, $like = null)
    {
        $model = $this->loadModel($id);
        if (!Yii::app()->user->checkAccess('Admin') && $model->id != Yii::app()->user->id)
        {
            throw new CHttpException(403, 'Access Denied.');
        }

        if (!$cat)
        {
            $cat = SnapUtil::config('boxomatic/supplier_product_feature_category');
        }

        if ($product)
        {
            $model->DontWant = array_merge($model->DontWant, array($product));
            $model->save();
            $model->refresh();
        }

        if ($like)
        {
            $newArr = array();
            foreach ($model->DontWant as $Product)
            {
                if ($Product->id != $like)
                {
                    $newArr[] = $Product->id;
                }
            }
            $model->DontWant = $newArr;
            $model->save();
            $model->refresh();
        }

        $Category = Category::model()->findByPk($cat);
        if ($cat == Category::uncategorisedCategory)
        {
            $SupplierProducts = SupplierProduct::getUncategorised();
        } else
        {
            $SupplierProducts = $Category->SupplierProducts;
        }

        $dontWantIds = array();
        foreach ($model->DontWant as $SP)
        {
            $dontWantIds[$SP->id] = $SP;
        }

        $this->render('dont_want', array(
            'model' => $model,
            'curCat' => $cat,
            'Category' => $Category,
            'SupplierProducts' => $SupplierProducts,
            'dontWantIds' => $dontWantIds,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = BoxomaticUser::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Displays the forgotten password page
     */
    public function actionForgottenPassword()
    {
        if (!Yii::app()->user->isGuest)
        {
            $this->redirect(Yii::app()->homeUrl);
        }

        $model = new ForgottenPasswordForm;
        $User = null;
        $mailError = false;

        // collect user input data
        if (isset($_POST['ForgottenPasswordForm']))
        {
            $model->scenario = 'changePassword';
            $model->attributes = $_POST['ForgottenPasswordForm'];

            // validate user input and redirect to the previous page if valid
            if ($model->validate())
            {
                $User = $model->User;
                $User->password_retrieval_key = $User->generatePassword(50, 4);
                $User->update_time = new CDbExpression('NOW()');
                $User->update();

                $adminEmail = SnapUtil::config('boxomatic/adminEmail');
                $adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
                $message = new YiiMailMessage('FoodBox password renewal');
                $message->view = 'forgottenPassword';

                $url = $this->createAbsoluteUrl('user/passwordReset', array('p' => $User->password_retrieval_key));

                //userModel is passed to the view
                $message->setBody(array('User' => $User, 'url' => $url), 'text/html');

                $message->addTo($User->email);
                $message->setFrom(array($adminEmail => $adminEmailFromName));

                if (!@Yii::app()->mail->send($message))
                {
                    $mailError = true;
                }
            }
        }

        // display the login form
        $this->render('forgottenPassword', array('model' => $model, 'User' => $User, 'mailError' => $mailError));
    }

    /**
     * Password reset form that the user is directed to in a password retrieval email
     */
    public function actionPasswordReset($p)
    {
        $model = User::model()->findByAttributes(array('password_retrieval_key' => $p), 'update_time > date_sub(NOW(), interval 1 hour)');
        $updateComplete = false;

        if (isset($_POST['User']))
        {
            $model->scenario = 'create';
            $model->attributes = $_POST['User'];
            $model->password = $_POST['User']['password'];
            if ($model->validate())
            {
                //clear our key so it can't be used again.
                $model->password_retrieval_key = '';
                $model->update_time = new CDbExpression('NOW()');
                $model->update();

                $updateComplete = true;
            }else
            {
                CVarDumper::dump($model->getErrors());
            }
        }

        $this->render('passwordReset', array(
            'model' => $model,
            'updateComplete' => $updateComplete,
        ));
    }

}
