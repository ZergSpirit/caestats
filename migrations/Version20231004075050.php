<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004075050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE elo_log (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, previous_elo_joueur1 INT NOT NULL, previous_elo_joueur2 INT NOT NULL, variation_elo_joueur1 INT NOT NULL, variation_elo_joueur2 INT NOT NULL, UNIQUE INDEX UNIQ_C3A63DA2E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE elo_log ADD CONSTRAINT FK_C3A63DA2E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elo_log DROP FOREIGN KEY FK_C3A63DA2E48FD905');
        $this->addSql('DROP TABLE elo_log');
    }
}
