<?php

/**
 * Test controller for PPButtonManager.
 *
 * CREATED: 2010-10-24
 * UPDATED: 2010-10-28
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPTestController extends Controller
{
	protected $bm = null;

	protected function beforeAction($action) {
		parent::beforeAction($action);
		$this->bm = Yii::app()->getModule('payPal')->buttonManager;
		return true;
	}

	public function actionCreate($name = "test2") {
		$b = $this->bm->createButton($name, array('BUTTONTYPE'=>'BUYNOW'));
		var_dump($b);
	}

	public function actionUpdate($name = "test2") {
		$b = $this->bm->updateButton($name, array('L_BUTTONVAR0'=>'amount=123.00'));
		var_dump($b);
	}

	public function actionGetDetails($name = "test2") {
		$b = $this->bm->getButtonDetails($name);
		var_dump($b);
	}

	public function actionStatus($name = "test2") {
		$b = $this->bm->manageButtonStatus($name);
		var_dump($b);
	}

	public function actionSearch($date = "2010-10-24T15:00:48Z") {
		$b = $this->bm->buttonSearch(array('STARTDATE'=>$date));
		var_dump($b);
	}

	public function actionSetInventory($name = "test2") {
		$b = $this->bm->setInventory($name, array('TRACKINV'=>'1',
		'TRACKPNL'=>'0','ITEMNUMBER'=>'0', 'ITEMQTY'=>'10', 'ITEMALERT'=>'0'));
		var_dump($b);
	}

	public function actionGetInventory($name = "test2") {
		$b = $this->bm->getInventory($name);
		var_dump($b);
	}

	public function actionIndex()
	{
		$this->renderText("PayPal test controller");
	}
}
