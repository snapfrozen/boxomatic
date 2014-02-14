<?php

class m131104_044547_create_menu_table extends CDbMigration
{
	public function up()
	{	
		$this->createTable('{{menu_items}}', array(
            'id' => 'pk',
            'path' => 'string NOT NULL',
			'title' => 'string NOT NULL',
			'parent' => 'int DEFAULT NULL',
			'menu_id' => 'varchar(50) NOT NULL',
			'content_id' => 'int DEFAULT NULL',
			'sort' => 'int NOT NULL DEFAULT 0',
			'external_path' => 'string DEFAULT NULL',
			'created' => 'DATETIME',
			'updated' => 'DATETIME',
        ));
		
		$this->addForeignKey('fk_menu_items_parent', '{{menu_items}}', 'parent', '{{menu_items}}', 'id', 'SET NULL', 'CASCADE');
		$this->addForeignKey('fk_menu_items_content', '{{menu_items}}', 'content_id', '{{content}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m131104_044547_create_menu_table does not support migration down.\n";
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