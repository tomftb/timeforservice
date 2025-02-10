<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209151653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD employe_id INT NOT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD21B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD21B65292 ON service (employe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD21B65292');
        $this->addSql('DROP INDEX IDX_E19D9AD21B65292 ON service');
        $this->addSql('ALTER TABLE service DROP employe_id');
    }
}
