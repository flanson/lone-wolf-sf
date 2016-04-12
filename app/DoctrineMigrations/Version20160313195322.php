<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160313195322 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE chapter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE enemy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hero_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE story_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chapter (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE enemy (id INT NOT NULL, name VARCHAR(255) NOT NULL, combatSkill INT NOT NULL, enduranceMax INT NOT NULL, life INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE hero (id INT NOT NULL, combatSkill INT NOT NULL, enduranceMax INT NOT NULL, life INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE story (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE chapter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE enemy_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hero_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE story_id_seq CASCADE');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE enemy');
        $this->addSql('DROP TABLE hero');
        $this->addSql('DROP TABLE story');
    }
}
