<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219212455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD active VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client_point ADD active VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE employe ADD active VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE client SET active="YES" WHERE 1;');
        $this->addSql('UPDATE client_point SET active="YES" WHERE 1;');
        $this->addSql('UPDATE employe SET active="YES" WHERE 1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP active');
        $this->addSql('ALTER TABLE client_point DROP active');
        $this->addSql('ALTER TABLE employe DROP active');
    }
}
