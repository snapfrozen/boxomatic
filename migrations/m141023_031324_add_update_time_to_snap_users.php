<?php

class m141023_031324_add_update_time_to_snap_users extends CDbMigration
{
	public function up()
	{
        $this->addColumn('snap_users', 'update_time', 'DATETIME NOT NULL');
	}

	public function down()
	{
		echo "m141023_031324_add_update_time_to_snap_users does not support migration down.\n";
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