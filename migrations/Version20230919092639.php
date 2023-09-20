<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919092639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE belligerant ADD vainqueur TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C773C35EE');
        $this->addSql('DROP INDEX UNIQ_232B318C773C35EE ON game');
        $this->addSql('ALTER TABLE game DROP vainqueur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE belligerant DROP vainqueur');
        $this->addSql('ALTER TABLE game ADD vainqueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C773C35EE FOREIGN KEY (vainqueur_id) REFERENCES belligerant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C773C35EE ON game (vainqueur_id)');
    }
}
