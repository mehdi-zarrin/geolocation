<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220830161349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resolved_address (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(3) NOT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, postcode VARCHAR(16) NOT NULL, lat NUMERIC(10, 0) DEFAULT NULL, lng NUMERIC(10, 0) DEFAULT NULL, INDEX search_idx (country_code, city, street, postcode), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE resolved_address');
    }
}
