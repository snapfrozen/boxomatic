<?php

class m140820_022927_add_supplier_product_item_sales_price extends CDbMigration
{

    public function up()
    {
        $this->addColumn('boxo_supplier_products', 'item_sales_price', 'DECIMAL(7,2)');
    }

    public function down()
    {
        echo "m140820_022927_add_supplier_product_item_sales_price does not support migration down.\n";
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
