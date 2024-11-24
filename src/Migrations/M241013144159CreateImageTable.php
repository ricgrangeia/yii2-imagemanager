<?php


namespace ricgrangeia\yii2ImageManager\Migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class M241013144159CreateImageTable extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {

		$tableName = '{{%imagemanager_image}}';

		if ( $this->db->schema->getTableSchema( $tableName, true ) === null ) {
			$this->createTable( $tableName, [
				'id' => $this->primaryKey(),

				'path' => $this->string( 250 )->notNull(),
				'img_name' => $this->string( 250 )->notNull(),
				'file_type' => $this->string( 10 )->notNull(),
				'filename' => $this->string( 250 )->notNull(),
				'id_model' => $this->integer()->notNull(),
				'name_model' => $this->string( 250 )->notNull(),

				'img_name_thumb' => $this->string( 250 )->null(),
				'category' => $this->string( 250 )->null(),
				'created_at' => $this->integer()->null()->comment( 'Created At' ),
				'updated_at' => $this->integer()->null()->comment( 'Updated At' ),
				'created_by' => $this->integer()->null()->comment( 'Created By' ),
				'updated_by' => $this->integer()->null()->comment( 'Updated By' ),
			] );
		}


	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {

		$tableName = '{{%imagemanager_image}}';

		if ( $this->db->schema->getTableSchema( $tableName, true ) !== null ) {
			$this->dropTable( $tableName );
		}
	}
}
