<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php if(Yii::app()->user->isGuest): ?>

<h2>Existing Customers</h2>

<p><a href="index.php?r=site/login">Click here to login</a></p>

<h2>New Customers</h2>

<p><a href="index.php?r=site/register">Click here to sign up</a></p>

<?php else: ?>


<h2>Place Orders</h2>

<p>You can order as far into the future as you like. However, we'll only pack your box if you have enough credit in your account.</p>

<p>It's perfectly fine to place orders into the future, then only put credit into your account on the weeks you'd like a box.</p>

<p><a href="index.php?r=customerBox/order">Click here to manage your orders</a></p>

<h2>Add Credit</h2>

<p>You can add credit to your account at any time.</p>

<p><a href="index.php?r=customerPayment/create">Click here to add credit</a></p>

<h2>Manage your profile</h2>

Your profile allows you to manage your contact details and your delivery locations</a></p>

<p><a href="index.php?r=user/update&id=<?php echo Yii::app()->user->id ?>">Click here to manage your profile</a></p>
	
<?php endif ?>