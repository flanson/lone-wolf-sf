<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160315210242 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE chapter ADD chapterValue INT NOT NULL');
        $this->addSql('ALTER TABLE chapter DROP name');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT fk_51ce6e8645b0bcd');
        $this->addSql('DROP INDEX uniq_51ce6e8645b0bcd');
        $this->addSql('ALTER TABLE hero ADD enemy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero RENAME COLUMN hero_id TO story_id');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E86AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E86900C982F FOREIGN KEY (enemy_id) REFERENCES enemy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E86AA5D4036 ON hero (story_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E86900C982F ON hero (enemy_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chapter ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapter DROP chapterValue');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E86AA5D4036');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E86900C982F');
        $this->addSql('DROP INDEX UNIQ_51CE6E86AA5D4036');
        $this->addSql('DROP INDEX UNIQ_51CE6E86900C982F');
        $this->addSql('ALTER TABLE hero ADD hero_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero DROP story_id');
        $this->addSql('ALTER TABLE hero DROP enemy_id');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT fk_51ce6e8645b0bcd FOREIGN KEY (hero_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_51ce6e8645b0bcd ON hero (hero_id)');
    }
}
