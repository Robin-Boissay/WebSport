<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222132810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_activiter_proprieter_type_activiter (type_activiter_id INT NOT NULL, proprieter_type_activiter_id INT NOT NULL, INDEX IDX_B8E3E5BA6473ECD7 (type_activiter_id), INDEX IDX_B8E3E5BADED27050 (proprieter_type_activiter_id), PRIMARY KEY(type_activiter_id, proprieter_type_activiter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type_activiter_proprieter_type_activiter ADD CONSTRAINT FK_B8E3E5BA6473ECD7 FOREIGN KEY (type_activiter_id) REFERENCES type_activiter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_activiter_proprieter_type_activiter ADD CONSTRAINT FK_B8E3E5BADED27050 FOREIGN KEY (proprieter_type_activiter_id) REFERENCES proprieter_type_activiter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_activiter_proprieter_type_activiter DROP FOREIGN KEY FK_B8E3E5BA6473ECD7');
        $this->addSql('ALTER TABLE type_activiter_proprieter_type_activiter DROP FOREIGN KEY FK_B8E3E5BADED27050');
        $this->addSql('DROP TABLE type_activiter_proprieter_type_activiter');
    }
}
