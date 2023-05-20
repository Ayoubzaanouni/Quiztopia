<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519222916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quiz_pt (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, nbr_tries INT NOT NULL, score DOUBLE PRECISION NOT NULL, joined_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_38B68099D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_pt_quizes (quiz_pt_id INT NOT NULL, quizes_id INT NOT NULL, INDEX IDX_D2CFFB19BEDE45BB (quiz_pt_id), INDEX IDX_D2CFFB19E0AE9030 (quizes_id), PRIMARY KEY(quiz_pt_id, quizes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_pt ADD CONSTRAINT FK_38B68099D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE quiz_pt_quizes ADD CONSTRAINT FK_D2CFFB19BEDE45BB FOREIGN KEY (quiz_pt_id) REFERENCES quiz_pt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_pt_quizes ADD CONSTRAINT FK_D2CFFB19E0AE9030 FOREIGN KEY (quizes_id) REFERENCES quizes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_participant_answers DROP FOREIGN KEY FK_A4C2362F79BF1BCE');
        $this->addSql('ALTER TABLE quiz_participant_answers DROP FOREIGN KEY FK_A4C2362FF786A939');
        $this->addSql('ALTER TABLE quiz_participant DROP FOREIGN KEY FK_F8B978B18337E7D7');
        $this->addSql('ALTER TABLE quiz_participant DROP FOREIGN KEY FK_F8B978B19D86650F');
        $this->addSql('DROP TABLE quiz_participant_answers');
        $this->addSql('DROP TABLE quiz_participant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quiz_participant_answers (quiz_participant_id INT NOT NULL, answers_id INT NOT NULL, INDEX IDX_A4C2362F79BF1BCE (answers_id), INDEX IDX_A4C2362FF786A939 (quiz_participant_id), PRIMARY KEY(quiz_participant_id, answers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quiz_participant (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, quiz_id_id INT NOT NULL, nbr_tries INT DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, joined_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F8B978B18337E7D7 (quiz_id_id), INDEX IDX_F8B978B19D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE quiz_participant_answers ADD CONSTRAINT FK_A4C2362F79BF1BCE FOREIGN KEY (answers_id) REFERENCES answers (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_participant_answers ADD CONSTRAINT FK_A4C2362FF786A939 FOREIGN KEY (quiz_participant_id) REFERENCES quiz_participant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_participant ADD CONSTRAINT FK_F8B978B18337E7D7 FOREIGN KEY (quiz_id_id) REFERENCES quizes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE quiz_participant ADD CONSTRAINT FK_F8B978B19D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE quiz_pt DROP FOREIGN KEY FK_38B68099D86650F');
        $this->addSql('ALTER TABLE quiz_pt_quizes DROP FOREIGN KEY FK_D2CFFB19BEDE45BB');
        $this->addSql('ALTER TABLE quiz_pt_quizes DROP FOREIGN KEY FK_D2CFFB19E0AE9030');
        $this->addSql('DROP TABLE quiz_pt');
        $this->addSql('DROP TABLE quiz_pt_quizes');
    }
}
