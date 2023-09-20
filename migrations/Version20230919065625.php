<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919065625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mission_combat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_controle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD mission_controle_id INT DEFAULT NULL, ADD mission_combat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C12166C47 FOREIGN KEY (mission_controle_id) REFERENCES mission_controle (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C4ECB1A91 FOREIGN KEY (mission_combat_id) REFERENCES mission_combat (id)');
        $this->addSql('CREATE INDEX IDX_232B318C12166C47 ON game (mission_controle_id)');
        $this->addSql('CREATE INDEX IDX_232B318C4ECB1A91 ON game (mission_combat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C4ECB1A91');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C12166C47');
        $this->addSql('DROP TABLE mission_combat');
        $this->addSql('DROP TABLE mission_controle');
        $this->addSql('DROP INDEX IDX_232B318C12166C47 ON game');
        $this->addSql('DROP INDEX IDX_232B318C4ECB1A91 ON game');
        $this->addSql('ALTER TABLE game DROP mission_controle_id, DROP mission_combat_id');
    }
}
