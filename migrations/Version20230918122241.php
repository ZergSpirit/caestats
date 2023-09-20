<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918122241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE belligerant (id INT AUTO_INCREMENT NOT NULL, joueur_id INT NOT NULL, compo_id INT NOT NULL, INDEX IDX_17DA6280A9E2D76C (joueur_id), INDEX IDX_17DA6280F1454301 (compo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compo (id INT AUTO_INCREMENT NOT NULL, guilde_id INT NOT NULL, INDEX IDX_C9C84099A2E96BBE (guilde_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compo_personnage (compo_id INT NOT NULL, personnage_id INT NOT NULL, INDEX IDX_53B78D28F1454301 (compo_id), INDEX IDX_53B78D285E315342 (personnage_id), PRIMARY KEY(compo_id, personnage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT DEFAULT NULL, belligerant1_id INT NOT NULL, belligerant2_id INT NOT NULL, vainqueur_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_232B318CF607770A (tournoi_id), UNIQUE INDEX UNIQ_232B318CAEDD5DDD (belligerant1_id), UNIQUE INDEX UNIQ_232B318CBC68F233 (belligerant2_id), UNIQUE INDEX UNIQ_232B318C773C35EE (vainqueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL, ville VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE belligerant ADD CONSTRAINT FK_17DA6280A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE belligerant ADD CONSTRAINT FK_17DA6280F1454301 FOREIGN KEY (compo_id) REFERENCES compo (id)');
        $this->addSql('ALTER TABLE compo ADD CONSTRAINT FK_C9C84099A2E96BBE FOREIGN KEY (guilde_id) REFERENCES guilde (id)');
        $this->addSql('ALTER TABLE compo_personnage ADD CONSTRAINT FK_53B78D28F1454301 FOREIGN KEY (compo_id) REFERENCES compo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compo_personnage ADD CONSTRAINT FK_53B78D285E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CF607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CAEDD5DDD FOREIGN KEY (belligerant1_id) REFERENCES belligerant (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CBC68F233 FOREIGN KEY (belligerant2_id) REFERENCES belligerant (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C773C35EE FOREIGN KEY (vainqueur_id) REFERENCES belligerant (id)');
        $this->addSql('ALTER TABLE personnage ADD ethere TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE belligerant DROP FOREIGN KEY FK_17DA6280A9E2D76C');
        $this->addSql('ALTER TABLE belligerant DROP FOREIGN KEY FK_17DA6280F1454301');
        $this->addSql('ALTER TABLE compo DROP FOREIGN KEY FK_C9C84099A2E96BBE');
        $this->addSql('ALTER TABLE compo_personnage DROP FOREIGN KEY FK_53B78D28F1454301');
        $this->addSql('ALTER TABLE compo_personnage DROP FOREIGN KEY FK_53B78D285E315342');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CF607770A');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CAEDD5DDD');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CBC68F233');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C773C35EE');
        $this->addSql('DROP TABLE belligerant');
        $this->addSql('DROP TABLE compo');
        $this->addSql('DROP TABLE compo_personnage');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('ALTER TABLE personnage DROP ethere');
    }
}
