<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128201952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_C242628E455FCC0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__module AS SELECT id, enseignant_id, nom, vol_horaire, coefficient FROM module');
        $this->addSql('DROP TABLE module');
        $this->addSql('CREATE TABLE module (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, enseignant_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, vol_horaire INTEGER NOT NULL, coefficient DOUBLE PRECISION NOT NULL, CONSTRAINT FK_C242628E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES prof (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO module (id, enseignant_id, nom, vol_horaire, coefficient) SELECT id, enseignant_id, nom, vol_horaire, coefficient FROM __temp__module');
        $this->addSql('DROP TABLE __temp__module');
        $this->addSql('CREATE INDEX IDX_C242628E455FCC0 ON module (enseignant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_C242628E455FCC0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__module AS SELECT id, enseignant_id, nom, vol_horaire, coefficient FROM module');
        $this->addSql('DROP TABLE module');
        $this->addSql('CREATE TABLE module (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, vol_horaire INTEGER NOT NULL, coefficient DOUBLE PRECISION NOT NULL, enseignant_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO module (id, enseignant_id, nom, vol_horaire, coefficient) SELECT id, enseignant_id, nom, vol_horaire, coefficient FROM __temp__module');
        $this->addSql('DROP TABLE __temp__module');
        $this->addSql('CREATE INDEX IDX_C242628E455FCC0 ON module (enseignant_id)');
    }
}
