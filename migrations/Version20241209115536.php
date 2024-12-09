<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209115536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B16BE0BCF');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B16BE0BCF FOREIGN KEY (etablishment_id) REFERENCES etablishment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B16BE0BCF');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B16BE0BCF FOREIGN KEY (etablishment_id) REFERENCES etablishment (id)');
    }
}
