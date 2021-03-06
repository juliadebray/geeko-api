<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118102857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', alchemist_tool_id INT NOT NULL, pseudo VARCHAR(255) NOT NULL, alchemist_level INT NOT NULL, INDEX IDX_81398E09EF9091B6 (alchemist_tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_6BAF7870C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE potion (id INT AUTO_INCREMENT NOT NULL, customer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', recipe_id INT DEFAULT NULL, type_id INT NOT NULL, value VARCHAR(4) NOT NULL, created_at DATETIME DEFAULT NULL, ingredients_list JSON DEFAULT NULL, INDEX IDX_4AB6B0AD9395C3F3 (customer_id), INDEX IDX_4AB6B0AD59D8A214 (recipe_id), INDEX IDX_4AB6B0ADC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE potion_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, ingredients_list JSON DEFAULT NULL, type VARCHAR(255) NOT NULL, average_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, token_password VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09EF9091B6 FOREIGN KEY (alchemist_tool_id) REFERENCES tool (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870C54C8C93 FOREIGN KEY (type_id) REFERENCES ingredient_type (id)');
        $this->addSql('ALTER TABLE potion ADD CONSTRAINT FK_4AB6B0AD9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE potion ADD CONSTRAINT FK_4AB6B0AD59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE potion ADD CONSTRAINT FK_4AB6B0ADC54C8C93 FOREIGN KEY (type_id) REFERENCES potion_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE potion DROP FOREIGN KEY FK_4AB6B0AD9395C3F3');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870C54C8C93');
        $this->addSql('ALTER TABLE potion DROP FOREIGN KEY FK_4AB6B0ADC54C8C93');
        $this->addSql('ALTER TABLE potion DROP FOREIGN KEY FK_4AB6B0AD59D8A214');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09EF9091B6');
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651BF396750');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09BF396750');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_type');
        $this->addSql('DROP TABLE potion');
        $this->addSql('DROP TABLE potion_type');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE user');
    }
}
