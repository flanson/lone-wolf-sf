<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160420144236 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE enemy ADD chapter_id INT NOT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA9579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FB9F5AA9579F4768 ON enemy (chapter_id)');
        $this->addSql('ALTER TABLE chapter ADD story_id INT NOT NULL');
        $this->addSql('ALTER TABLE chapter ADD directions TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN chapter.directions IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F981B52EAA5D4036 ON chapter (story_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chapter DROP CONSTRAINT FK_F981B52EAA5D4036');
        $this->addSql('DROP INDEX IDX_F981B52EAA5D4036');
        $this->addSql('ALTER TABLE chapter DROP story_id');
        $this->addSql('ALTER TABLE chapter DROP directions');
        $this->addSql('ALTER TABLE enemy DROP CONSTRAINT FK_FB9F5AA9579F4768');
        $this->addSql('DROP INDEX IDX_FB9F5AA9579F4768');
        $this->addSql('ALTER TABLE enemy DROP chapter_id');
    }
}
