<?php

use yii\db\Migration;

class m220707_094251_links extends Migration
{
    public function up()
    {
        $this->createTable('links', [
          'id' => $this->primaryKey(),
          'month' => $this->string(255)->notNull(),
          'shorted_link' => $this->string(255)->notNull(),
          'short_code' => $this->string(255)->notNull(),
          'cliks' => $this->integer()->notNull()->defaultValue(0),
          'top_position' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('links');
    }
}
