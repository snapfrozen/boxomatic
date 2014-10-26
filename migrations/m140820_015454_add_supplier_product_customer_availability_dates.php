<?php

class m140820_015454_add_supplier_product_customer_availability_dates extends CDbMigration
{

    public function up()
    {
        $this->addColumn('boxo_supplier_products','customer_available_from', 'DATE');
        $this->addColumn('boxo_supplier_products','customer_available_to', 'DATE');
    }

    public function down()
    {
        echo "m140820_015454_add_supplier_product_customer_availability_dates does not support migration down.\n";
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
