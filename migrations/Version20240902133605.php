<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902133605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_point ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_point ADD CONSTRAINT FK_14C86F9319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_14C86F9319EB6921 ON client_point (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_point DROP FOREIGN KEY FK_14C86F9319EB6921');
        $this->addSql('DROP INDEX IDX_14C86F9319EB6921 ON client_point');
        $this->addSql('ALTER TABLE client_point DROP client_id');
    }
}
