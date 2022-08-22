<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822085125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_allergen TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plantypes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, setup_time VARCHAR(255) NOT NULL, rest_time VARCHAR(255) NOT NULL, steps VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_public TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes_ingredients (recipes_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_761206B0FDF2B1FA (recipes_id), INDEX IDX_761206B03EC4DCE (ingredients_id), PRIMARY KEY(recipes_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes_plantypes (recipes_id INT NOT NULL, plantypes_id INT NOT NULL, INDEX IDX_99A70FB7FDF2B1FA (recipes_id), INDEX IDX_99A70FB744459A22 (plantypes_id), PRIMARY KEY(recipes_id, plantypes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipes_ingredients ADD CONSTRAINT FK_761206B0FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_ingredients ADD CONSTRAINT FK_761206B03EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_plantypes ADD CONSTRAINT FK_99A70FB7FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_plantypes ADD CONSTRAINT FK_99A70FB744459A22 FOREIGN KEY (plantypes_id) REFERENCES plantypes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes_ingredients DROP FOREIGN KEY FK_761206B0FDF2B1FA');
        $this->addSql('ALTER TABLE recipes_ingredients DROP FOREIGN KEY FK_761206B03EC4DCE');
        $this->addSql('ALTER TABLE recipes_plantypes DROP FOREIGN KEY FK_99A70FB7FDF2B1FA');
        $this->addSql('ALTER TABLE recipes_plantypes DROP FOREIGN KEY FK_99A70FB744459A22');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE plantypes');
        $this->addSql('DROP TABLE recipes');
        $this->addSql('DROP TABLE recipes_ingredients');
        $this->addSql('DROP TABLE recipes_plantypes');
    }
}
