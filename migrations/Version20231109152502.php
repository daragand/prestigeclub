<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109152502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_club (user_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_C26F74BBA76ED395 (user_id), INDEX IDX_C26F74BB61190A32 (club_id), PRIMARY KEY(user_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_club ADD CONSTRAINT FK_C26F74BBA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_club ADD CONSTRAINT FK_C26F74BB61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(50) NOT NULL, ADD lastname VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_club DROP FOREIGN KEY FK_C26F74BBA76ED395');
        $this->addSql('ALTER TABLE user_club DROP FOREIGN KEY FK_C26F74BB61190A32');
        $this->addSql('DROP TABLE user_club');
        $this->addSql('ALTER TABLE `user` DROP firstname, DROP lastname');
    }
}
