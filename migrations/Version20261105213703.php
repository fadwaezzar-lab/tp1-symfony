<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261105213703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD contenu LONGTEXT NOT NULL, DROP contenue, CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE autre auteur VARCHAR(100) NOT NULL, CHANGE date date_creation DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD contenue VARCHAR(255) NOT NULL, DROP contenu, CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE auteur autre VARCHAR(100) NOT NULL, CHANGE date_creation date DATETIME NOT NULL');
    }
}
