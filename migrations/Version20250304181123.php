<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304181123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD client_point_name VARCHAR(255) DEFAULT NULL, ADD client_point_street VARCHAR(255) DEFAULT NULL, ADD client_point_zip_code VARCHAR(255) DEFAULT NULL, ADD client_point_town VARCHAR(255) DEFAULT NULL, ADD client_point_email VARCHAR(255) DEFAULT NULL, ADD client_point_phone_number VARCHAR(255) DEFAULT NULL, ADD client_name VARCHAR(255) DEFAULT NULL, ADD client_street VARCHAR(100) DEFAULT NULL, ADD client_zip_code VARCHAR(100) DEFAULT NULL, ADD client_town VARCHAR(100) DEFAULT NULL, ADD client_nin VARCHAR(255) DEFAULT NULL, ADD client_email VARCHAR(100) DEFAULT NULL, ADD client_send_notify VARCHAR(255) NOT NULL, ADD client_point_send_notify VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE `service` SET `client_point_send_notify`=(SELECT `client_point`.`send_notify` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_point_name`=(SELECT `client_point`.`name` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_point_street`=(SELECT `client_point`.`street` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_point_zip_code`=(SELECT `client_point`.`zip_code` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_point_town`=(SELECT `client_point`.`town` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_point_email`=(SELECT `client_point`.`email` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_point_phone_number`=(SELECT `client_point`.`phone_number` FROM `client_point` WHERE `client_point`.`id`=`service`.`client_point_id` ) WHERE `service`.`id`>0;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_point_name` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_point_street` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_point_zip_code` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_point_town` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_point_email` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_point_phone_number` VARCHAR(255) NOT NULL;');
        $this->addSql('UPDATE `service` SET `client_send_notify`=(SELECT `client`.`send_notify` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_name`=(SELECT `client`.`name` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_street`=(SELECT `client`.`street` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_zip_code`=(SELECT `client`.`zip_code` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_town`=(SELECT `client`.`town` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_nin`=(SELECT `client`.`nin` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_email`=(SELECT `client`.`email` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('UPDATE `service` SET `client_send_notify`=(SELECT `client`.`send_notify` FROM `client` WHERE `client`.`id`=(SELECT `client_point`.`client_id` FROM `client_point` WHERE `client_point`.`id` = `service`.`client_point_id`)) WHERE `service`.`id`>0;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_name` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_street` VARCHAR(100) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_zip_code` VARCHAR(100) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_town` VARCHAR(100) NOT NULL;');
        $this->addSql('ALTER TABLE `service` MODIFY COLUMN `client_nin` VARCHAR(255) NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP client_point_name, DROP client_point_street, DROP client_point_zip_code, DROP client_point_town, DROP client_point_email, DROP client_point_phone_number, DROP client_name, DROP client_street, DROP client_zip_code, DROP client_town, DROP client_nin, DROP client_email, DROP client_send_notify, DROP client_point_send_notify');
    }
}
