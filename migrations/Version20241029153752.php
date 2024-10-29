<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029153752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification_of_activities DROP classification_id');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD client_id INT NOT NULL, ADD classification_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT FK_9A51741919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT FK_9A5174192A86559F FOREIGN KEY (classification_id) REFERENCES classification_of_activities (id)');
        $this->addSql('CREATE INDEX IDX_9A51741919EB6921 ON client_classification_of_activities (client_id)');
        $this->addSql('CREATE INDEX IDX_9A5174192A86559F ON client_classification_of_activities (classification_id)');
        $this->addSql('ALTER TABLE client_classification_of_activities ADD CONSTRAINT UC_client_classification UNIQUE (client_id,classification_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification_of_activities ADD classification_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP FOREIGN KEY FK_9A51741919EB6921');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP FOREIGN KEY FK_9A5174192A86559F');
        $this->addSql('DROP INDEX IDX_9A51741919EB6921 ON client_classification_of_activities');
        $this->addSql('DROP INDEX IDX_9A5174192A86559F ON client_classification_of_activities');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP INDEX UC_client_classification');
        $this->addSql('ALTER TABLE client_classification_of_activities DROP client_id, DROP classification_id');
        
    }
}
