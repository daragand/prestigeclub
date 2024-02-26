<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201193033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photo_order (photo_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_4F78942F7E9E4C8C (photo_id), INDEX IDX_4F78942F8D9F6D38 (order_id), PRIMARY KEY(photo_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo_order ADD CONSTRAINT FK_4F78942F7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_order ADD CONSTRAINT FK_4F78942F8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo_order DROP FOREIGN KEY FK_4F78942F7E9E4C8C');
        $this->addSql('ALTER TABLE photo_order DROP FOREIGN KEY FK_4F78942F8D9F6D38');
        $this->addSql('DROP TABLE photo_order');
    }
}
