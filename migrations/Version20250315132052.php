<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315132052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification_of_activities ADD deleted VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE client_point_name client_point_name VARCHAR(255) DEFAULT NULL, CHANGE client_point_street client_point_street VARCHAR(255) DEFAULT NULL, CHANGE client_point_zip_code client_point_zip_code VARCHAR(255) DEFAULT NULL, CHANGE client_point_town client_point_town VARCHAR(255) DEFAULT NULL, CHANGE client_point_email client_point_email VARCHAR(255) DEFAULT NULL, CHANGE client_point_phone_number client_point_phone_number VARCHAR(255) DEFAULT NULL, CHANGE client_name client_name VARCHAR(255) DEFAULT NULL, CHANGE client_street client_street VARCHAR(100) DEFAULT NULL, CHANGE client_zip_code client_zip_code VARCHAR(100) DEFAULT NULL, CHANGE client_town client_town VARCHAR(100) DEFAULT NULL, CHANGE client_nin client_nin VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE `classification_of_activities` SET `deleted`="NO" WHERE `classification_of_activities`.`id`>0;');
        $this->addSql('ALTER TABLE `classification_of_activities` MODIFY COLUMN `deleted` VARCHAR(255) NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification_of_activities DROP deleted');
        $this->addSql('ALTER TABLE service CHANGE client_point_name client_point_name VARCHAR(255) NOT NULL, CHANGE client_point_street client_point_street VARCHAR(255) NOT NULL, CHANGE client_point_zip_code client_point_zip_code VARCHAR(255) NOT NULL, CHANGE client_point_town client_point_town VARCHAR(255) NOT NULL, CHANGE client_point_email client_point_email VARCHAR(255) NOT NULL, CHANGE client_point_phone_number client_point_phone_number VARCHAR(255) NOT NULL, CHANGE client_name client_name VARCHAR(255) NOT NULL, CHANGE client_street client_street VARCHAR(100) NOT NULL, CHANGE client_zip_code client_zip_code VARCHAR(100) NOT NULL, CHANGE client_town client_town VARCHAR(100) NOT NULL, CHANGE client_nin client_nin VARCHAR(255) NOT NULL');
    }
}
