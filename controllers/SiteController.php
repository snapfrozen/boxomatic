<?php

class SiteController extends Controller
{

    public $Content;
    public $meta_keywords = '';
    public $meta_description = '';
    public $meta_author = '';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

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
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'contact', 'error', 'login', 'logout', 'captcha', 'register', 'getImage'),
                'roles' => array('View Content'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $homeId = SnapUtil::config('general/site.homepage_id');
        $Content = Content::model()->findByPk($homeId);

        //Used by the admin bar
        $this->Content = $Content;

        $this->layout = '//layouts/column1';
        $view = '/content/view';

        if ($this->getLayoutFile('//layouts/content_types/' . $Content->type))
            $this->layout = '//layouts/content_types/' . $Content->type;

        if ($this->getViewFile('/content/content_types/' . $Content->type))
            $view = '/content/content_types/' . $Content->type;

        $News = Content::model()->findAllByAttributes(array('type' => 'news'), array(
            'limit' => 4,
            'order' => 'created DESC',
            'condition' =>
            '(publish_on < NOW() OR publish_on is null) AND ' .
            '(unpublish_on < NOW() OR unpublish_on is null) AND ' .
            'published = 1'
        ));

        $this->render($view, array(
            'Content' => $Content,
            'News' => $News,
            'MenuItem' => false,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm']))
        {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate())
            {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm']))
        {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $BoxoCart = new BoxoCart;
                $BoxoCart->populateCart();
                //$this->redirect(Yii::app()->user->returnUrl);
                $this->redirect(array('shop/index'));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Get an associated with this model
     * @param type $id
     * @param type $attribute
     */
    public function actionGetImage($id, $field, $modelName = 'Content', $w = null, $h = null, $zc = null)
    {
        $model = $modelName::model()->findByPk($id);
        $base = Yii::getPathOfAlias('frontend.data');

        $filePath = dirname(Yii::app()->request->scriptFile) . '/' . $base . '/' . strtolower($modelName) . '/' . $field . '_' . $id;
        if (empty($model->$field) || !file_exists($filePath))
        {
            $filePath = dirname(Yii::app()->request->scriptFile) . '/' . $base . '/default.jpg';
        }

        $image = $model->$field;
        $_GET['src'] = $filePath;
        $_GET['w'] = $w;
        $_GET['h'] = $h;
        $_GET['zc'] = $zc;

        include(Yii::getPathOfAlias('backend.external.PHPThumb') . '/PHPThumb.php');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
