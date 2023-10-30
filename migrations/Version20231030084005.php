<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030084005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranktable DROP FOREIGN KEY FK_8879E8E5A9E2D76C');
        $this->addSql('ALTER TABLE ranktable DROP FOREIGN KEY FK_8879E8E5F607770A');
        $this->addSql('DROP INDEX idx_8879e8e5f607770a ON ranktable');
        $this->addSql('CREATE INDEX IDX_ADACEFA2F607770A ON ranktable (tournoi_id)');
        $this->addSql('DROP INDEX idx_8879e8e5a9e2d76c ON ranktable');
        $this->addSql('CREATE INDEX IDX_ADACEFA2A9E2D76C ON ranktable (joueur_id)');
        $this->addSql('ALTER TABLE ranktable ADD CONSTRAINT FK_8879E8E5A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE ranktable ADD CONSTRAINT FK_8879E8E5F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE tournoi ADD zits_fading_month_elapsed INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranktable DROP FOREIGN KEY FK_ADACEFA2F607770A');
        $this->addSql('ALTER TABLE ranktable DROP FOREIGN KEY FK_ADACEFA2A9E2D76C');
        $this->addSql('DROP INDEX idx_adacefa2f607770a ON ranktable');
        $this->addSql('CREATE INDEX IDX_8879E8E5F607770A ON ranktable (tournoi_id)');
        $this->addSql('DROP INDEX idx_adacefa2a9e2d76c ON ranktable');
        $this->addSql('CREATE INDEX IDX_8879E8E5A9E2D76C ON ranktable (joueur_id)');
        $this->addSql('ALTER TABLE ranktable ADD CONSTRAINT FK_ADACEFA2F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE ranktable ADD CONSTRAINT FK_ADACEFA2A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE tournoi DROP zits_fading_month_elapsed');
    }
}
