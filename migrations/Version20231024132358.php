<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024132358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ranktable (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT NOT NULL, joueur_id INT NOT NULL, position INT NOT NULL, INDEX IDX_8879E8E5F607770A (tournoi_id), INDEX IDX_8879E8E5A9E2D76C (joueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ranktable ADD CONSTRAINT FK_8879E8E5F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE ranktable ADD CONSTRAINT FK_8879E8E5A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE joueur ADD zits INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tournoi ADD zits_cote INT DEFAULT NULL, ADD nb_participants INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranktable DROP FOREIGN KEY FK_8879E8E5F607770A');
        $this->addSql('ALTER TABLE ranktable DROP FOREIGN KEY FK_8879E8E5A9E2D76C');
        $this->addSql('DROP TABLE ranktable');
        $this->addSql('ALTER TABLE joueur DROP zits');
        $this->addSql('ALTER TABLE tournoi DROP zits_cote, DROP nb_participants');
    }
}
