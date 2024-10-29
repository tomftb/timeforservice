<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029140421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classification_of_activities (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(100) NOT NULL, unit VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_classification_of_activities (id INT AUTO_INCREMENT NOT NULL, id_client_id INT NOT NULL, id_classification_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_9A51741999DED506 (id_client_id), INDEX IDX_9A517419DBB13D88 (id_classification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT FK_9A51741999DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT FK_9A517419DBB13D88 FOREIGN KEY (id_classification_id) REFERENCES classification_of_activities (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_classification_of_activities DROP FOREIGN KEY FK_9A51741999DED506');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP FOREIGN KEY FK_9A517419DBB13D88');
        $this->addSql('DROP TABLE classification_of_activities');
        $this->addSql('DROP TABLE client_classification_of_activities');
    }
}
