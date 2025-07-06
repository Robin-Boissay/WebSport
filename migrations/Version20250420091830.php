<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420091830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activiter_exercice (id INT AUTO_INCREMENT NOT NULL, activiter_id_id INT NOT NULL, type_activiter_id INT NOT NULL, INDEX IDX_338F7BF62274EEF3 (activiter_id_id), INDEX IDX_338F7BF66473ECD7 (type_activiter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activiter_exercice ADD CONSTRAINT FK_338F7BF62274EEF3 FOREIGN KEY (activiter_id_id) REFERENCES activiter (id)');
        $this->addSql('ALTER TABLE activiter_exercice ADD CONSTRAINT FK_338F7BF66473ECD7 FOREIGN KEY (type_activiter_id) REFERENCES type_activiter (id)');
        $this->addSql('ALTER TABLE data_activiter CHANGE valeur valeur INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activiter_exercice DROP FOREIGN KEY FK_338F7BF62274EEF3');
        $this->addSql('ALTER TABLE activiter_exercice DROP FOREIGN KEY FK_338F7BF66473ECD7');
        $this->addSql('DROP TABLE activiter_exercice');
        $this->addSql('ALTER TABLE data_activiter CHANGE valeur valeur DOUBLE PRECISION NOT NULL');
    }
}
