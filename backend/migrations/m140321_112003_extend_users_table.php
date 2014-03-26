<?php

class m140321_112003_extend_users_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('{{users}}', 'notes', 'VARCHAR(255)');
		$this->addColumn('{{users}}', 'user_phone', 'VARCHAR(45)');
		$this->addColumn('{{users}}', 'user_mobile', 'VARCHAR(45)');
		$this->addColumn('{{users}}', 'user_address', 'VARCHAR(45)');
		$this->addColumn('{{users}}', 'user_address2', 'VARCHAR(45)');
		$this->addColumn('{{users}}', 'user_suburb', 'VARCHAR(45)');
		$this->addColumn('{{users}}', 'user_state', 'VARCHAR(45)');
		$this->addColumn('{{users}}', 'user_postcode', 'VARCHAR(255)');
		$this->addColumn('{{users}}', 'password_retrieval_key', 'VARCHAR(255)');
		$this->addColumn('{{users}}', 'auto_login_key', 'VARCHAR(255)');
		$this->addColumn('{{users}}', 'last_login_time', 'DATETIME');
	}

	public function down()
	{
		echo "m140321_112003_extend_users_table does not support migration down.\n";
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