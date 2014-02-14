<?php

class m131104_044546_create_content_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('{{content}}', array(
            'id' => 'pk',
            'title' => 'string NOT NULL',
			'type' => 'string NOT NULL',
			'published' => 'TINYINT(1)',
			'created' => 'DATETIME',
			'updated' => 'DATETIME',
        ));
	}

	public function down()
	{
		echo "m131104_085408_create_content_table does not support migration down.\n";
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