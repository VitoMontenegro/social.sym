<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327233836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth (id INT AUTO_INCREMENT NOT NULL, salt VARCHAR(255) NOT NULL, blocked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devices (id INT AUTO_INCREMENT NOT NULL, updated DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', authid INT NOT NULL, blocked TINYINT(1) NOT NULL, INDEX uuid_x (uuid), UNIQUE INDEX unique_devices (authid, uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, authid INT NOT NULL, cod INT NOT NULL, email VARCHAR(50) NOT NULL, UNIQUE INDEX unique_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profiles (id INT AUTO_INCREMENT NOT NULL, authid INT NOT NULL, gender VARCHAR(50) DEFAULT NULL, birthday VARCHAR(50) DEFAULT NULL, name VARCHAR(250) NOT NULL, surname VARCHAR(255) DEFAULT NULL, patronymic VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX id_x (id), UNIQUE INDEX unique_authid (authid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE auth');
        $this->addSql('DROP TABLE devices');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE user_profiles');
    }
}
