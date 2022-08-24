<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220824064826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_ingredients (user_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_E27BD8DAA76ED395 (user_id), INDEX IDX_E27BD8DA3EC4DCE (ingredients_id), PRIMARY KEY(user_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_plantypes (user_id INT NOT NULL, plantypes_id INT NOT NULL, INDEX IDX_1123B83DA76ED395 (user_id), INDEX IDX_1123B83D44459A22 (plantypes_id), PRIMARY KEY(user_id, plantypes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_ingredients ADD CONSTRAINT FK_E27BD8DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_ingredients ADD CONSTRAINT FK_E27BD8DA3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_plantypes ADD CONSTRAINT FK_1123B83DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_plantypes ADD CONSTRAINT FK_1123B83D44459A22 FOREIGN KEY (plantypes_id) REFERENCES plantypes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_ingredients DROP FOREIGN KEY FK_E27BD8DAA76ED395');
        $this->addSql('ALTER TABLE user_ingredients DROP FOREIGN KEY FK_E27BD8DA3EC4DCE');
        $this->addSql('ALTER TABLE user_plantypes DROP FOREIGN KEY FK_1123B83DA76ED395');
        $this->addSql('ALTER TABLE user_plantypes DROP FOREIGN KEY FK_1123B83D44459A22');
        $this->addSql('DROP TABLE user_ingredients');
        $this->addSql('DROP TABLE user_plantypes');
    }
}
