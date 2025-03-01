<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222141719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proprieter_type_activiter_type_activiter (proprieter_type_activiter_id INT NOT NULL, type_activiter_id INT NOT NULL, INDEX IDX_B67D9D1ADED27050 (proprieter_type_activiter_id), INDEX IDX_B67D9D1A6473ECD7 (type_activiter_id), PRIMARY KEY(proprieter_type_activiter_id, type_activiter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proprieter_type_activiter_type_activiter ADD CONSTRAINT FK_B67D9D1ADED27050 FOREIGN KEY (proprieter_type_activiter_id) REFERENCES proprieter_type_activiter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proprieter_type_activiter_type_activiter ADD CONSTRAINT FK_B67D9D1A6473ECD7 FOREIGN KEY (type_activiter_id) REFERENCES type_activiter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activiter ADD type_activiter_id INT NOT NULL');
        $this->addSql('ALTER TABLE activiter ADD CONSTRAINT FK_16C6E236473ECD7 FOREIGN KEY (type_activiter_id) REFERENCES type_activiter (id)');
        $this->addSql('CREATE INDEX IDX_16C6E236473ECD7 ON activiter (type_activiter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proprieter_type_activiter_type_activiter DROP FOREIGN KEY FK_B67D9D1ADED27050');
        $this->addSql('ALTER TABLE proprieter_type_activiter_type_activiter DROP FOREIGN KEY FK_B67D9D1A6473ECD7');
        $this->addSql('DROP TABLE proprieter_type_activiter_type_activiter');
        $this->addSql('ALTER TABLE activiter DROP FOREIGN KEY FK_16C6E236473ECD7');
        $this->addSql('DROP INDEX IDX_16C6E236473ECD7 ON activiter');
        $this->addSql('ALTER TABLE activiter DROP type_activiter_id');
    }
}
