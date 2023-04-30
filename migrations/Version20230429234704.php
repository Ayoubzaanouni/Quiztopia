<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230429234704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answers (id INT AUTO_INCREMENT NOT NULL, quest_id_id INT NOT NULL, quiz_id_id INT NOT NULL, text LONGTEXT NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_50D0C6062CF907CB (quest_id_id), INDEX IDX_50D0C6068337E7D7 (quiz_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, quiz_id_id INT NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_8ADC54D58337E7D7 (quiz_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_pt (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, nbr_tries INT NOT NULL, score DOUBLE PRECISION NOT NULL, joined_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_38B68099D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_pt_quizes (quiz_pt_id INT NOT NULL, quizes_id INT NOT NULL, INDEX IDX_D2CFFB19BEDE45BB (quiz_pt_id), INDEX IDX_D2CFFB19E0AE9030 (quizes_id), PRIMARY KEY(quiz_pt_id, quizes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizes (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, code VARCHAR(5) NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', max_tries INT DEFAULT NULL, INDEX IDX_8E40FA139D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6062CF907CB FOREIGN KEY (quest_id_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6068337E7D7 FOREIGN KEY (quiz_id_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D58337E7D7 FOREIGN KEY (quiz_id_id) REFERENCES quizes (id)');
        $this->addSql('ALTER TABLE quiz_pt ADD CONSTRAINT FK_38B68099D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE quiz_pt_quizes ADD CONSTRAINT FK_D2CFFB19BEDE45BB FOREIGN KEY (quiz_pt_id) REFERENCES quiz_pt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_pt_quizes ADD CONSTRAINT FK_D2CFFB19E0AE9030 FOREIGN KEY (quizes_id) REFERENCES quizes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA139D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6062CF907CB');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6068337E7D7');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D58337E7D7');
        $this->addSql('ALTER TABLE quiz_pt DROP FOREIGN KEY FK_38B68099D86650F');
        $this->addSql('ALTER TABLE quiz_pt_quizes DROP FOREIGN KEY FK_D2CFFB19BEDE45BB');
        $this->addSql('ALTER TABLE quiz_pt_quizes DROP FOREIGN KEY FK_D2CFFB19E0AE9030');
        $this->addSql('ALTER TABLE quizes DROP FOREIGN KEY FK_8E40FA139D86650F');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE quiz_pt');
        $this->addSql('DROP TABLE quiz_pt_quizes');
        $this->addSql('DROP TABLE quizes');
    }
}
