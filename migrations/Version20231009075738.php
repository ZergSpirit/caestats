<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009075738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compo CHANGE no_stats no_stats TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE game CHANGE no_ranking no_ranking TINYINT(1) NOT NULL, CHANGE no_stats no_stats TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compo CHANGE no_stats no_stats TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE game CHANGE no_ranking no_ranking TINYINT(1) DEFAULT 0 NOT NULL, CHANGE no_stats no_stats TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
