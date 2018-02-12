<?php declare(strict_types = 1);

namespace WouterDeSchuyter\WhenLol\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180212141945 extends AbstractMigration
{
    private const TABLE = 'gallery_item';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable(self::TABLE);
        $table->addColumn('id', 'uuid');
        $table->addColumn('text', 'string')->setLength(64);
        $table->addColumn('author_ip', 'string')->setLength(64);
        $table->addColumn('author_user_agent', 'string')->setLength(255);
        $table->addColumn('created_at', 'datetime')->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn('updated_at', 'datetime')->setNotnull(false);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['author_ip']);
        $table->addIndex(['author_user_agent']);
        $table->addIndex(['created_at']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE);
    }
}
