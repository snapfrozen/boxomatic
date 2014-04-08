<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo Yii::app()->name ?> password retrieval</title>
</head>
<body style="margin:0;">
	<p>Hi <?php echo $User->full_name ?>,</p>
	<p>Please go to <a href="<?php echo $url ?>"><?php echo $url ?></a> within 1 hour to reset your password.</p>
<p>
--<br />
Kind regards,<br />
Box-O-Matic, Box Processing System</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:<?php echo SnapUtil::config('boxomatic/adminEmail') ?>"><?php echo SnapUtil::config('boxomatic/adminEmail') ?></a></p>

</body>
</html>
