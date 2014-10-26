<?php

class CronController extends BoxomaticController
{

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
     * Generate new delivery dates and boxes for each date
     */
    public function actionCreateFutureDeliveryDatesAndBoxes()
    {
        $weeksInAdvance = SnapUtil::config('boxomatic/autoCreateDeliveryDates');

        $latestDate = DeliveryDate::getLastEnteredDate();
        if ($latestDate)
            $latestDate = strtotime($latestDate->date);
        else
            $latestDate = time();

        $targetDate = strtotime('+' . $weeksInAdvance . ' weeks');
        $BoxSizes = BoxSize::model()->findAll();

        while ($latestDate <= $targetDate)
        {
//			$dateStr = date('j-n-Y',$latestDate);
//			$parts = explode('-',$dateStr);
//			mktime(0,0,0,$parts[1],$parts[0],$parts[2]);

            foreach (SnapUtil::config('boxomatic/deliveryDateLocations') as $day => $locationIds)
            {
                if (!empty($locationIds))
                {
                    $latestDate = strtotime('next ' . $day, $latestDate);
                    //var_dump(date('l, d-m-Y',$latestDate));
                    //$latestDateStr=date('Y-m-d',$latestDate);
                    //$latestDate=strtotime($latestDateStr . ' +1 week');
                    $newDateStr = date('Y-m-d', $latestDate);

                    $DeliveryDate = new DeliveryDate;
                    $DeliveryDate->date = $newDateStr;
                    $DeliveryDate->Locations = $locationIds;
                    $DeliveryDate->save();

                    foreach ($BoxSizes as $BoxSize)
                    {
                        $Box = new Box;
                        $Box->size_id = $BoxSize->id;
                        $Box->box_price = $BoxSize->box_size_price;
                        $Box->delivery_date_id = $DeliveryDate->id;
                        $Box->save();
                    }
                    echo '<p>Created new delivery_date: ' . $DeliveryDate->date . '</p>';
                }
            }
        }

        echo '<p><strong>Finished.</strong></p>';

        Yii::app()->end();
    }

    /**
     * Send reminder emails to those who haven't paid for their next week's box
     */
    public function actionSendReminderEmails()
    {
        $Customers = Customer::model()->findAllWithNoOrders();
        foreach ($Customers as $Cust)
        {
            $validator = new CEmailValidator();
            if ($validator->validateValue(trim($Cust->User->email)))
            {
                $User = $Cust->User;
                $User->auto_login_key = $User->generatePassword(50, 4);
                $User->update_time = new CDbExpression('NOW()');
                $User->update();

                $adminEmail = SnapUtil::config('boxomatic/adminEmail');
                $adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
                $message = new YiiMailMessage('Running out of orders');
                $message->view = 'customer_running_out_of_orders';
                $message->setBody(array('Customer' => $Cust, 'User' => $User), 'text/html');
                $message->addTo($Cust->User->email);
                $message->addBcc($adminEmail);
                //$message->addTo('francis.beresford@gmail.com');
                $message->setFrom(array($adminEmail => $adminEmailFromName));

                if (!@Yii::app()->mail->send($message))
                {
                    echo '<p style="color:red"><strong>Email failed sending to: ' . $Cust->User->email . '</strong></p>';
                } else
                {
                    echo '<p>Running out of orders message sent to: ' . $Cust->User->email . '</p>';
                }
            } else
            {
                echo '<p style="color:red"><strong>Email not valid: "' . $Cust->User->email . '"</strong></p>';
            }
        }

        echo '<p><strong>Finished.</strong></p>';
        //Yii::app()->end();
    }

}
