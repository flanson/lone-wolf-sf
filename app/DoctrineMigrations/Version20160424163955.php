<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160424163955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE campaign_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE campaign (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE adventure DROP CONSTRAINT FK_9E858E0FAA5D4036');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0FAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE combat DROP CONSTRAINT FK_8D51E398900C982F');
        $this->addSql('ALTER TABLE combat ADD CONSTRAINT FK_8D51E398900C982F FOREIGN KEY (enemy_id) REFERENCES enemy (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE story ADD campaign_id INT NOT NULL');
        $this->addSql('ALTER TABLE story ADD active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EB560438F639F774 ON story (campaign_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE story DROP CONSTRAINT FK_EB560438F639F774');
        $this->addSql('DROP SEQUENCE campaign_id_seq CASCADE');
        $this->addSql('DROP TABLE campaign');
        $this->addSql('DROP INDEX IDX_EB560438F639F774');
        $this->addSql('ALTER TABLE story DROP campaign_id');
        $this->addSql('ALTER TABLE story DROP active');
        $this->addSql('ALTER TABLE combat DROP CONSTRAINT fk_8d51e398900c982f');
        $this->addSql('ALTER TABLE combat ADD CONSTRAINT fk_8d51e398900c982f FOREIGN KEY (enemy_id) REFERENCES enemy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE adventure DROP CONSTRAINT fk_9e858e0faa5d4036');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT fk_9e858e0faa5d4036 FOREIGN KEY (story_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
