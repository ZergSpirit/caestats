<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918095141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guilde (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnage (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnage_guilde (personnage_id INT NOT NULL, guilde_id INT NOT NULL, INDEX IDX_680DCAF95E315342 (personnage_id), INDEX IDX_680DCAF9A2E96BBE (guilde_id), PRIMARY KEY(personnage_id, guilde_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personnage_guilde ADD CONSTRAINT FK_680DCAF95E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage_guilde ADD CONSTRAINT FK_680DCAF9A2E96BBE FOREIGN KEY (guilde_id) REFERENCES guilde (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnage_guilde DROP FOREIGN KEY FK_680DCAF95E315342');
        $this->addSql('ALTER TABLE personnage_guilde DROP FOREIGN KEY FK_680DCAF9A2E96BBE');
        $this->addSql('DROP TABLE guilde');
        $this->addSql('DROP TABLE personnage');
        $this->addSql('DROP TABLE personnage_guilde');
    }
}
