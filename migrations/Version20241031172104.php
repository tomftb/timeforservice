<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031172104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UC_classification_of_activities_code ON classification_of_activities');
        $this->addSql('DROP INDEX UC_classification_of_activities_name ON classification_of_activities');
        $this->addSql('DROP INDEX UC_client_classification ON client_classification_of_activities');
        $this->addSql('ALTER TABLE service ADD route DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UC_classification_of_activities_code ON classification_of_activities (code)');
        $this->addSql('CREATE UNIQUE INDEX UC_classification_of_activities_name ON classification_of_activities (name)');
        $this->addSql('CREATE UNIQUE INDEX UC_client_classification ON client_classification_of_activities (client_id, classification_id)');
        $this->addSql('ALTER TABLE service DROP route');
    }
}
