<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261115100716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the tache table for task management';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tache (
            id INT AUTO_INCREMENT NOT NULL,
            titre VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            terminee TINYINT(1) NOT NULL,
            date_creation DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tache');
    }
}
