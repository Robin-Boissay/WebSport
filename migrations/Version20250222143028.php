<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222143028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_activiter ADD proprieter_activiter_id INT NOT NULL');
        $this->addSql('ALTER TABLE data_activiter ADD CONSTRAINT FK_8EC32E1527CF445 FOREIGN KEY (proprieter_activiter_id) REFERENCES proprieter_type_activiter (id)');
        $this->addSql('CREATE INDEX IDX_8EC32E1527CF445 ON data_activiter (proprieter_activiter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_activiter DROP FOREIGN KEY FK_8EC32E1527CF445');
        $this->addSql('DROP INDEX IDX_8EC32E1527CF445 ON data_activiter');
        $this->addSql('ALTER TABLE data_activiter DROP proprieter_activiter_id');
    }
}
