<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029142556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_classification_of_activities DROP FOREIGN KEY FK_9A517419DBB13D88');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP FOREIGN KEY FK_9A51741999DED506');
        $this->addSql('DROP INDEX IDX_9A517419DBB13D88 ON client_classification_of_activities');
        $this->addSql('DROP INDEX UNIQ_9A51741999DED506 ON client_classification_of_activities');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP id_client_id, DROP id_classification_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_classification_of_activities ADD id_client_id INT NOT NULL, ADD id_classification_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT FK_9A517419DBB13D88 FOREIGN KEY (id_classification_id) REFERENCES classification_of_activities (id)');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT FK_9A51741999DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9A517419DBB13D88 ON client_classification_of_activities (id_classification_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9A51741999DED506 ON client_classification_of_activities (id_client_id)');
    }
}
