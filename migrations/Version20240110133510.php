<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110133510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_club (group_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_91B46302FE54D947 (group_id), INDEX IDX_91B4630261190A32 (club_id), PRIMARY KEY(group_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_club ADD CONSTRAINT FK_91B46302FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_club ADD CONSTRAINT FK_91B4630261190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_group DROP FOREIGN KEY FK_CDAE6E7761190A32');
        $this->addSql('ALTER TABLE club_group DROP FOREIGN KEY FK_CDAE6E77FE54D947');
        $this->addSql('DROP TABLE club_group');
        $this->addSql('ALTER TABLE option_list DROP FOREIGN KEY FK_ACC652FB301EC62');
        $this->addSql('ALTER TABLE option_list ADD CONSTRAINT FK_ACC652FB301EC62 FOREIGN KEY (photos_id) REFERENCES photo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club_group (club_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_CDAE6E7761190A32 (club_id), INDEX IDX_CDAE6E77FE54D947 (group_id), PRIMARY KEY(club_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE club_group ADD CONSTRAINT FK_CDAE6E7761190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_group ADD CONSTRAINT FK_CDAE6E77FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_club DROP FOREIGN KEY FK_91B46302FE54D947');
        $this->addSql('ALTER TABLE group_club DROP FOREIGN KEY FK_91B4630261190A32');
        $this->addSql('DROP TABLE group_club');
        $this->addSql('ALTER TABLE option_list DROP FOREIGN KEY FK_ACC652FB301EC62');
        $this->addSql('ALTER TABLE option_list ADD CONSTRAINT FK_ACC652FB301EC62 FOREIGN KEY (photos_id) REFERENCES photo (id) ON DELETE CASCADE');
    }
}
