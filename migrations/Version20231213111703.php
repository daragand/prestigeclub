<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213111703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981AD5CDBF');
        $this->addSql('DROP INDEX IDX_F52993981AD5CDBF ON `order`');
        $this->addSql('ALTER TABLE `order` ADD forfait_id INT DEFAULT NULL, DROP cart_id');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398906D5F2C FOREIGN KEY (forfait_id) REFERENCES forfait (id)');
        $this->addSql('CREATE INDEX IDX_F5299398906D5F2C ON `order` (forfait_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398906D5F2C');
        $this->addSql('DROP INDEX IDX_F5299398906D5F2C ON `order`');
        $this->addSql('ALTER TABLE `order` ADD cart_id INT NOT NULL, DROP forfait_id');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993981AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F52993981AD5CDBF ON `order` (cart_id)');
    }
}
