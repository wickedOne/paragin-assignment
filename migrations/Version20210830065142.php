<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830065142 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Question (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', remindo_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME NOT NULL, sequence INT NOT NULL, max INT NOT NULL, INDEX IDX_4F812B18F60B01B4 (remindo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Remindo (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Respondent (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', remindo_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FC65280F60B01B4 (remindo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Result (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', remindo_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', respondent_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', question_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME NOT NULL, score DOUBLE PRECISION NOT NULL, INDEX IDX_14C6C425F60B01B4 (remindo_id), INDEX IDX_14C6C425CE80CD19 (respondent_id), INDEX IDX_14C6C4251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Question ADD CONSTRAINT FK_4F812B18F60B01B4 FOREIGN KEY (remindo_id) REFERENCES Remindo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Respondent ADD CONSTRAINT FK_FC65280F60B01B4 FOREIGN KEY (remindo_id) REFERENCES Remindo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Result ADD CONSTRAINT FK_14C6C425F60B01B4 FOREIGN KEY (remindo_id) REFERENCES Remindo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Result ADD CONSTRAINT FK_14C6C425CE80CD19 FOREIGN KEY (respondent_id) REFERENCES Respondent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Result ADD CONSTRAINT FK_14C6C4251E27F6BF FOREIGN KEY (question_id) REFERENCES Question (id) ON DELETE CASCADE');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Result DROP FOREIGN KEY FK_14C6C4251E27F6BF');
        $this->addSql('ALTER TABLE Question DROP FOREIGN KEY FK_4F812B18F60B01B4');
        $this->addSql('ALTER TABLE Respondent DROP FOREIGN KEY FK_FC65280F60B01B4');
        $this->addSql('ALTER TABLE Result DROP FOREIGN KEY FK_14C6C425F60B01B4');
        $this->addSql('ALTER TABLE Result DROP FOREIGN KEY FK_14C6C425CE80CD19');
        $this->addSql('DROP TABLE Question');
        $this->addSql('DROP TABLE Remindo');
        $this->addSql('DROP TABLE Respondent');
        $this->addSql('DROP TABLE Result');
    }
}
