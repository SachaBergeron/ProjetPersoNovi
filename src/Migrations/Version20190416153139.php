<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416153139 extends AbstractMigration
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
        $this->addSql('CREATE TABLE module (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, enseignant_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, coefficient DOUBLE PRECISION NOT NULL, vol_horaire_td INTEGER NOT NULL, vol_horaire_tp INTEGER NOT NULL, vol_horaire_cm INTEGER NOT NULL, CONSTRAINT FK_C242628E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES prof (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO module (id, enseignant_id, nom, vol_horaire_td, coefficient) SELECT id, enseignant_id, nom, vol_horaire, coefficient FROM __temp__module');
        $this->addSql('DROP TABLE __temp__module');
        $this->addSql('CREATE INDEX IDX_C242628E455FCC0 ON module (enseignant_id)');
        $this->addSql('DROP INDEX IDX_E39396EAFC2B591');
        $this->addSql('CREATE TEMPORARY TABLE __temp__controle AS SELECT id, module_id, nom, coefficient, date, note_max FROM controle');
        $this->addSql('DROP TABLE controle');
        $this->addSql('CREATE TABLE controle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, module_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, coefficient DOUBLE PRECISION NOT NULL, date DATE NOT NULL, note_max DOUBLE PRECISION NOT NULL, CONSTRAINT FK_E39396EAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO controle (id, module_id, nom, coefficient, date, note_max) SELECT id, module_id, nom, coefficient, date, note_max FROM __temp__controle');
        $this->addSql('DROP TABLE __temp__controle');
        $this->addSql('CREATE INDEX IDX_E39396EAFC2B591 ON controle (module_id)');
        $this->addSql('DROP INDEX IDX_CFBDFA14758926A6');
        $this->addSql('DROP INDEX IDX_CFBDFA14DDEAB1A3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__note AS SELECT id, controle_id, etudiant_id, note FROM note');
        $this->addSql('DROP TABLE note');
        $this->addSql('CREATE TABLE note (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, controle_id INTEGER NOT NULL, etudiant_id INTEGER NOT NULL, note DOUBLE PRECISION NOT NULL, CONSTRAINT FK_CFBDFA14758926A6 FOREIGN KEY (controle_id) REFERENCES controle (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CFBDFA14DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO note (id, controle_id, etudiant_id, note) SELECT id, controle_id, etudiant_id, note FROM __temp__note');
        $this->addSql('DROP TABLE __temp__note');
        $this->addSql('CREATE INDEX IDX_CFBDFA14758926A6 ON note (controle_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14DDEAB1A3 ON note (etudiant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_E39396EAFC2B591');
        $this->addSql('CREATE TEMPORARY TABLE __temp__controle AS SELECT id, module_id, nom, coefficient, date, note_max FROM controle');
        $this->addSql('DROP TABLE controle');
        $this->addSql('CREATE TABLE controle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, module_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, coefficient DOUBLE PRECISION NOT NULL, date DATE NOT NULL, note_max DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO controle (id, module_id, nom, coefficient, date, note_max) SELECT id, module_id, nom, coefficient, date, note_max FROM __temp__controle');
        $this->addSql('DROP TABLE __temp__controle');
        $this->addSql('CREATE INDEX IDX_E39396EAFC2B591 ON controle (module_id)');
        $this->addSql('DROP INDEX IDX_C242628E455FCC0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__module AS SELECT id, enseignant_id, nom, coefficient FROM module');
        $this->addSql('DROP TABLE module');
        $this->addSql('CREATE TABLE module (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, enseignant_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, coefficient DOUBLE PRECISION NOT NULL, vol_horaire INTEGER NOT NULL)');
        $this->addSql('INSERT INTO module (id, enseignant_id, nom, coefficient) SELECT id, enseignant_id, nom, coefficient FROM __temp__module');
        $this->addSql('DROP TABLE __temp__module');
        $this->addSql('CREATE INDEX IDX_C242628E455FCC0 ON module (enseignant_id)');
        $this->addSql('DROP INDEX IDX_CFBDFA14758926A6');
        $this->addSql('DROP INDEX IDX_CFBDFA14DDEAB1A3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__note AS SELECT id, controle_id, etudiant_id, note FROM note');
        $this->addSql('DROP TABLE note');
        $this->addSql('CREATE TABLE note (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, controle_id INTEGER NOT NULL, etudiant_id INTEGER NOT NULL, note DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO note (id, controle_id, etudiant_id, note) SELECT id, controle_id, etudiant_id, note FROM __temp__note');
        $this->addSql('DROP TABLE __temp__note');
        $this->addSql('CREATE INDEX IDX_CFBDFA14758926A6 ON note (controle_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14DDEAB1A3 ON note (etudiant_id)');
    }
}
