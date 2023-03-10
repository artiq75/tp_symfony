<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301123118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, is_seen TINYINT(1) DEFAULT 0 NOT NULL, published_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, children INT NOT NULL, adults INT NOT NULL, pool_acess TINYINT(1) NOT NULL, grant_access TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, customer_firstname VARCHAR(255) NOT NULL, customer_lastname VARCHAR(255) NOT NULL, customer_address VARCHAR(255) NOT NULL, INDEX IDX_E00CEDDE549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(255) NOT NULL, company_address VARCHAR(255) NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_address VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) DEFAULT 1 NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, adults INT NOT NULL, children INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_line (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, unit_price INT NOT NULL, fill_order INT NOT NULL, days INT NOT NULL, type INT NOT NULL, designation VARCHAR(255) NOT NULL, INDEX IDX_D3D1D6932989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, owner_id INT NOT NULL, availability_start DATE DEFAULT NULL, availability_end DATE DEFAULT NULL, created_at DATETIME NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, adult_rate INT NOT NULL, child_rate INT DEFAULT 0 NOT NULL, INDEX IDX_8BF21CDEC54C8C93 (type_id), INDEX IDX_8BF21CDE7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6932989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEC54C8C93 FOREIGN KEY (type_id) REFERENCES property_type (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE549213EC');
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6932989F1FD');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEC54C8C93');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE7E3C61F9');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_line');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
