<?php

class m141024_031812_add_new_value_config extends CDbMigration {

    public function up() {
        
    }

    public function down() {
        echo "m141024_031812_add_new_value_config does not support migration down.\n";
        return false;
    }

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->insert('snap_config', array('config_loc' => 'boxomatic/redirectBoxCategory','value'=>3));
    }

    /*
      public function safeDown()
      {
      }
     */
}
