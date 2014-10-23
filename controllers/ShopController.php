<?php

class ShopController extends Controller {

    public $Content;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl',
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('error'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('index', 'contact', 'login', 'shop', 'captcha', 'register', 'checkout', 'changeLocation', 'process'),
                'roles' => array('View Content'),
            ),
            array('allow',
                'actions' => array('welcome', 'removeOrder', 'confirmOrder', 'revertChanges'),
                'roles' => array('customer'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Manages all models.
     */
    public function actionIndex($cat = null) {
        if (!$cat) {
            $cat = SnapUtil::config('boxomatic/supplier_product_feature_category');
        }

        $BoxoCart = new BoxoCart;
        if (isset($_GET['set-date'])) {
            $BoxoCart->setDelivery_date_id($_GET['set-date']);
        }
        if (isset($_POST['BoxoCart']['location_id'])) {
            $BoxoCart->location_id = $_POST['BoxoCart']['location_id'];
            $this->refresh();
        }

        if (isset($_POST['Order']) && isset($_POST['add_to_cart'])) {
            if ($BoxoCart->addItems($_POST['Order'])) {
                Yii::app()->user->setFlash('success', 'Item added to cart.');
            }
            $this->refresh();
        }

        if (isset($_POST['Order']) && (isset($_POST['update_cart']) || isset($_POST['checkout']))) {
            if ($BoxoCart->updateItems($_POST['Order'])) {
                Yii::app()->user->setFlash('success', 'Cart updated.');
            }
            if (isset($_POST['checkout'])) {
                $this->redirect(array('shop/checkout'));
            }
            $this->refresh();
        }

        $userId = Yii::app()->user->id;
        $User = BoxomaticUser::model()->findByPk($userId);
        $Category = Category::model()->findByPk($cat);

        $DeliveryDate = false;
        $dpProducts = false;
        $Location = $BoxoCart->Location;

        if ($Location) {
            if (!$BoxoCart->DeliveryDate) {
                $DeliveryDate = $BoxoCart->getNextDeliveryDate();
            } else {
                $DeliveryDate = $BoxoCart->DeliveryDate;
            }
            $BoxoCart->setDelivery_date_id($DeliveryDate->id);

            $products = SupplierProduct::getAvailableItems($DeliveryDate->id, $cat);
            $dpProducts = new CActiveDataProvider('SupplierProduct');
            $dpProducts->setData($products);
        };

        $this->render('index', array(
            'dpProducts' => $dpProducts,
            'DeliveryDate' => $DeliveryDate,
            'Category' => $Category,
            'Customer' => $User,
            'curCat' => $cat,
            'BoxoCart' => $BoxoCart,
            'Location' => $Location,
        ));
    }

    public function actionConfirmOrder() {
        $BoxoCart = new BoxoCart;
        $minDays = SnapUtil::config('boxomatic/minimumAdvancePayment');
        $nextTotal = $BoxoCart->getNextTotal($minDays);
        if ($nextTotal !== 0) {
            Yii::app()->user->setFlash('danger', 'You must add at least <strong>' . SnapFormat::currency($nextTotal) . '</strong> to your account to do this.');
        } else {
            $BoxoCart = new BoxoCart;
            $BoxoCart->confirmOrder();
            Yii::app()->user->setFlash('success', 'Your order has been updated.');
        }
        $this->redirect(array('/shop/checkout'));
    }

    public function actionChangeLocation() {
        $user = Yii::app()->user;
        if ($user->isGuest) {
            $BoxoCart = new BoxoCart;
            $BoxoCart->setLocation_id(null);
            $this->redirect(array('/shop/index'));
        } else {
            $this->redirect(array('/user/update', 'id' => $user->id));
        }
    }

    public function actionRemoveOrder($id) {
        $BoxoCart = new BoxoCart;
        $BoxoCart->removeOrder($id);
        Yii::app()->user->setFlash('success', 'Order removed.');
        $this->redirect(array('/shop/checkout'));
    }

    public function actionRevertChanges() {
        $BoxoCart = new BoxoCart;
        $BoxoCart->revertChanges();
        Yii::app()->user->setFlash('success', 'Changes reverted.');
        $this->redirect(array('/shop/checkout'));
    }

    public function actionCheckout() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('/shop/register'));
        }

        $userId = Yii::app()->user->id;
        $User = BoxomaticUser::model()->findByPk($userId);
        if (!$User->Location) {
            Yii::app()->user->setFlash('warning', 'Please set your location');
            $this->redirect(array('/user/update', 'id' => $User->id));
        }

        $DeliveryDates = DeliveryDate::model()->with('Boxes')->findAll(array(
            'condition' => 'DATE_SUB(date, INTERVAL -1 week) > NOW() AND date < DATE_ADD(NOW(), INTERVAL 1 MONTH)',
                //'limit'=>$show
        ));

        $BoxoCart = new BoxoCart;
        if (isset($_GET['set-date'])) {
            $BoxoCart->setDelivery_date_id($_GET['set-date']);
        }

        $AllDeliveryDates = DeliveryDate::model()->with('Locations')->findAll('Locations.location_id = :locationId', array(
            ':locationId' => $User->location_id,
        ));

        if (isset($_POST['btn_recurring'])) { //recurring order button pressed
            $NextDD = $BoxoCart->getLastDeliveryDate();
            $DDs = $BoxoCart->Location->getFutureDeliveryDates($NextDD, (int) $_POST['months_advance'], $_POST['every']);
            $allOk = $BoxoCart->repeatCurrentOrder($DDs);
            if (!$allOk) {
                Yii::app()->user->setFlash('warning', '<strong>Warning:</strong> One or more of the products are not available on the given dates and have been removed.');
            }
        }
        $this->render('checkout', array(
            'BoxoCart' => $BoxoCart,
            'DeliveryDate' => $BoxoCart->DeliveryDate,
            'Customer' => $User,
            'AllDeliveryDates' => $AllDeliveryDates,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(SnapUtil::config('boxomatic/adminEmail'), $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionRegister() {
        $BoxoCart = new BoxoCart;
        $Customer = new BoxomaticUser;
        $vars = array();

        if (isset($_POST['BoxomaticUser'])) {
            $Customer->attributes = $_POST['BoxomaticUser'];
            $Customer->scenario = 'register';

            if ($Customer->save()) {
                if (!$Customer->Location->is_pickup) {
                    $UserLoc = new UserLocation;
                    $UserLoc->user_id = $Customer->id;
                    $UserLoc->location_id = $Customer->location_id;
                    $UserLoc->address = $Customer->user_address;
                    $UserLoc->address2 = $Customer->user_address2;
                    $UserLoc->suburb = $Customer->user_suburb;
                    $UserLoc->state = $Customer->user_state;
                    $UserLoc->postcode = $Customer->user_postcode;
                    $UserLoc->phone = !empty($Customer->user_phone) ? $Customer->user_phone : $Customer->user_mobile;
                    $UserLoc->save(false);
                    $Customer->user_location_id = $UserLoc->customer_location_id;
                }

                $Auth = Yii::app()->authManager;
                $Auth->assign('customer', $Customer->id);

                $adminEmail = SnapUtil::config('boxomatic/adminEmail');
                $adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
                //Send email
                $message = new YiiMailMessage('Welcome to ' . Yii::app()->name);
                $message->view = 'welcome';
                $message->setBody(array('User' => $Customer, 'newPassword' => $_POST['BoxomaticUser']['password']), 'text/html');
                $message->addTo($adminEmail);
                $message->addTo($Customer->email);
                $message->setFrom(array($adminEmail => $adminEmailFromName));

                if (!@Yii::app()->mail->send($message)) {
                    $mailError = true;
                }

                $identity = new UserIdentity($Customer->email, $_POST['BoxomaticUser']['password']);
                $identity->authenticate();

                Yii::app()->user->login($identity);
                BoxomaticUser::model()->updateByPk($identity->id, array('last_login_time' => new CDbExpression('NOW()')));

                $this->redirect(array('shop/checkout'));
            }
        } else {
            $Customer->location_id = $BoxoCart->location_id;
        }

        $Customer->password = '';
        $Customer->password_repeat = '';
        $vars['model'] = $Customer;

        // $this->render('register',array('model'=>$Customer));
        $this->render('register', $vars);
    }

    /**
     * Welcome message.
     */
    public function actionWelcome() {
        $User = BoxomaticUser::model()->findByPk(Yii::app()->user->id);
        $this->render('welcome', array('User' => $User));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * @author Chienlv levanchien.it@gmail.com
     * @todo action process payment, support Paypal and Pin
     */
    public function actionProcess() {
        $data = isset($_POST) ? $_POST : false;
        if ($data) #{
            switch ($data['payment-method']) {
                /**
                 * @todo Default is Paypal
                 * @link http://stackoverflow.com/questions/14843212/submit-form-via-curl-and-redirect-browser-to-paypal tutoria
                 */
                default :
                case 0:
                    #Delete $ data ['payment-method'] because it is not for paypal
                    unset($data['paymenok'
                            . 't-method']);
                    #The variable $data obtained from the form sent to
                    header('Location: https://www.paypal.com/cgi-bin/webscr?' . http_build_query($data));
                    break;
                /**
                 * @todo use Pin paymant
                 * Cac tham so gui tu from
                 */
                case 1:
                    if (isset($_POST['card'])) {
                        $data = $_POST;
                        $data['amount'] = number_format($data['amount'], 2, '.', '');
                        $gateway = \Omnipay\Common\GatewayFactory::create('Pin');
                        $gateway->setSecretKey(Yii::app()->params['Pin']['secret_key']);
                        $gateway->setTestMode(true); // remove this line in production
                        $response = $gateway->purchase($data)->send();
                        if ($response->isSuccessful()) {
                            // payment was successful: update database
                            $data2 = $response->getData();
                            $model = new UserPayment();
                            $model->payment_date = new CDbExpression('NOW()');
                            $model->payment_type = "CREDIT-PIN";
                            $model->payment_value = number_format($data2['response']['amount']/100,2);
                            $model->user_id = Yii::app()->user->id;
                            $model->staff_id = null;
                            $model->payment_note = $data2['response']['token'];
                            $model->save();
                            
                            $BoxoCart = new BoxoCart;
                            $BoxoCart->confirmOrder();
                            
                            $this->redirect(array('user/payments'));
                        } elseif ($response->isRedirect()) {
                            // redirect to offsite payment gateway
                            $response->redirect();
                        } else {
                            // payment failed: display message to customer
                            Yii::app()->user->setFlash('warning', $response->getMessage());
                        }
                        unset($data['card']);
                            
                    }

                    $this->render('_pin', array('data' => $data));
                    break;
            }
    }

}
