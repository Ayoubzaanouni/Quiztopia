<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430195732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes DROP FOREIGN KEY FK_8E40FA139D86650F');
        $this->addSql('DROP INDEX IDX_8E40FA139D86650F ON quizes');
        $this->addSql('ALTER TABLE quizes CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizes MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA139D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8E40FA139D86650F ON quizes (user_id_id)');
    }
}
