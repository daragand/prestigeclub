<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109133154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE licencie_group (licencie_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_6A6A6B7DB56DCD74 (licencie_id), INDEX IDX_6A6A6B7DFE54D947 (group_id), PRIMARY KEY(licencie_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE licencie_group ADD CONSTRAINT FK_6A6A6B7DB56DCD74 FOREIGN KEY (licencie_id) REFERENCES licencie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE licencie_group ADD CONSTRAINT FK_6A6A6B7DFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licencie_group DROP FOREIGN KEY FK_6A6A6B7DB56DCD74');
        $this->addSql('ALTER TABLE licencie_group DROP FOREIGN KEY FK_6A6A6B7DFE54D947');
        $this->addSql('DROP TABLE licencie_group');
    }
}
