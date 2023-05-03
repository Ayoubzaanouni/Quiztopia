<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502193007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers ADD quiz_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6068337E7D7 FOREIGN KEY (quiz_id_id) REFERENCES quizes (id)');
        $this->addSql('CREATE INDEX IDX_50D0C6068337E7D7 ON answers (quiz_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6068337E7D7');
        $this->addSql('DROP INDEX IDX_50D0C6068337E7D7 ON answers');
        $this->addSql('ALTER TABLE answers DROP quiz_id_id');
    }
}
