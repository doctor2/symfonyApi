<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405173256 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE airport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, timezone VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, departure_airport_id INT NOT NULL, arrival_airport_id INT NOT NULL, departure_time DATETIME NOT NULL, arrival_time DATETIME NOT NULL, INDEX IDX_97A0ADA3F631AB5C (departure_airport_id), INDEX IDX_97A0ADA37F43E343 (arrival_airport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F631AB5C FOREIGN KEY (departure_airport_id) REFERENCES airport (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37F43E343 FOREIGN KEY (arrival_airport_id) REFERENCES airport (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F631AB5C');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA37F43E343');
        $this->addSql('DROP TABLE airport');
        $this->addSql('DROP TABLE ticket');
    }
}
