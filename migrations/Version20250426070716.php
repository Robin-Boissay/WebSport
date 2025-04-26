<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426070716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(255) DEFAULT NULL, unit VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_type_activiter (evenement_id INT NOT NULL, type_activiter_id INT NOT NULL, INDEX IDX_1D4F54BFFD02F13 (evenement_id), INDEX IDX_1D4F54BF6473ECD7 (type_activiter_id), PRIMARY KEY(evenement_id, type_activiter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement_type_activiter ADD CONSTRAINT FK_1D4F54BFFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_type_activiter ADD CONSTRAINT FK_1D4F54BF6473ECD7 FOREIGN KEY (type_activiter_id) REFERENCES type_activiter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement_type_activiter DROP FOREIGN KEY FK_1D4F54BFFD02F13');
        $this->addSql('ALTER TABLE evenement_type_activiter DROP FOREIGN KEY FK_1D4F54BF6473ECD7');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE evenement_type_activiter');
    }
}
