<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210133758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_E7D6FCC198260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite_tag (specialite_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_D5C1B8EC2195E0F0 (specialite_id), INDEX IDX_D5C1B8ECBAD26311 (tag_id), PRIMARY KEY(specialite_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specialite ADD CONSTRAINT FK_E7D6FCC198260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE specialite_tag ADD CONSTRAINT FK_D5C1B8EC2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialite_tag ADD CONSTRAINT FK_D5C1B8ECBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialite DROP FOREIGN KEY FK_E7D6FCC198260155');
        $this->addSql('ALTER TABLE specialite_tag DROP FOREIGN KEY FK_D5C1B8EC2195E0F0');
        $this->addSql('ALTER TABLE specialite_tag DROP FOREIGN KEY FK_D5C1B8ECBAD26311');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE specialite_tag');
        $this->addSql('DROP TABLE tag');
    }
}
