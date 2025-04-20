<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420092422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_activiter ADD activiter_exercice_id INT NOT NULL');
        $this->addSql('ALTER TABLE data_activiter ADD CONSTRAINT FK_8EC32E1566C4F4ED FOREIGN KEY (activiter_exercice_id) REFERENCES activiter_exercice (id)');
        $this->addSql('CREATE INDEX IDX_8EC32E1566C4F4ED ON data_activiter (activiter_exercice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_activiter DROP FOREIGN KEY FK_8EC32E1566C4F4ED');
        $this->addSql('DROP INDEX IDX_8EC32E1566C4F4ED ON data_activiter');
        $this->addSql('ALTER TABLE data_activiter DROP activiter_exercice_id');
    }
}
