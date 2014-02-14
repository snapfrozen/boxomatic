TheCKEditor
===========

Use this extension to integrate CKEditor (http://ckeditor.com/) in Yii framework.

With this Yii extension you can use the CKEditor as an inputwidget.

Updated by: Ali Qanavatian
this extension is an updated edition of yii-fckeditor-integration




GNU LESSER GENERAL PUBLIC LICENSE



This program is free software: you can redistribute it and/or modify

it under the terms of the GNU lesser General Public License as published by

the Free Software Foundation, either version 3 of the License, or

(at your option) any later version.



This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

GNU General Public License for more details.



You should have received a copy of the GNU lesser General Public License

along with this program.  If not, see <http://www.gnu.org/licenses/>.



Requirements:

The CK-Editor have to be installed and configured. The Editor itself is

not included to this extension.



This extension have to be installed into:

{{{

<Yii-Application>/protected/extensions/TheCKEditor

}}}

Usage:

{{{

<?php $this->widget('application.extensions.TheCKEditor.theCKEditorWidget',array(

	'model'=>$pages,				# Data-Model (form model)

	'attribute'=>'content',			# Attribute in the Data-Model

	'height'=>'400px',

	'width'=>'100%',

	'toolbarSet'=>'Basic', 			# EXISTING(!) Toolbar (see: ckeditor.js)

	'ckeditor'=>Yii::app()->basePath.'/../ckeditor/ckeditor.php',

									# Path to ckeditor.php

	'ckBasePath'=>Yii::app()->baseUrl.'/ckeditor/',

									# Relative Path to the Editor (from Web-Root)

	'css' => Yii::app()->baseUrl.'/css/index.css',

									# Additional Parameters

) ); ?>

}}}



more optional parameters:

	'config' => array('toolbar'=>array(

	     array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),

	    array( 'Image', 'Link', 'Unlink', 'Anchor' ),

	 ),);


