<?php

class m140409_035700_supplier_products_add_options_field extends CDbMigration
{
	public function up()
	{
		$tablePrefix = SnapUtil::config('boxomatic/tablePrefix');
		$this->addColumn($tablePrefix.'supplier_products','quantity_options','string');
	}

	public function down()
	{
		echo "m140409_035700_supplier_products_add_options_field does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}