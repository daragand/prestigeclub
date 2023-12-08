<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208082710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE option_list (id INT AUTO_INCREMENT NOT NULL, photos_id INT NOT NULL, options_id INT NOT NULL, cart_id INT DEFAULT NULL, orders_id INT DEFAULT NULL, quantity INT NOT NULL, statut VARCHAR(50) NOT NULL, INDEX IDX_ACC652FB301EC62 (photos_id), INDEX IDX_ACC652FB3ADB05F1 (options_id), INDEX IDX_ACC652FB1AD5CDBF (cart_id), INDEX IDX_ACC652FBCFFE9AD6 (orders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE option_list ADD CONSTRAINT FK_ACC652FB301EC62 FOREIGN KEY (photos_id) REFERENCES photo (id)');
        $this->addSql('ALTER TABLE option_list ADD CONSTRAINT FK_ACC652FB3ADB05F1 FOREIGN KEY (options_id) REFERENCES options (id)');
        $this->addSql('ALTER TABLE option_list ADD CONSTRAINT FK_ACC652FB1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE option_list ADD CONSTRAINT FK_ACC652FBCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE cart_options DROP FOREIGN KEY FK_2BEF32801AD5CDBF');
        $this->addSql('ALTER TABLE cart_options DROP FOREIGN KEY FK_2BEF32803ADB05F1');
        $this->addSql('DROP TABLE cart_options');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_options (cart_id INT NOT NULL, options_id INT NOT NULL, INDEX IDX_2BEF32801AD5CDBF (cart_id), INDEX IDX_2BEF32803ADB05F1 (options_id), PRIMARY KEY(cart_id, options_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_options ADD CONSTRAINT FK_2BEF32801AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_options ADD CONSTRAINT FK_2BEF32803ADB05F1 FOREIGN KEY (options_id) REFERENCES options (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_list DROP FOREIGN KEY FK_ACC652FB301EC62');
        $this->addSql('ALTER TABLE option_list DROP FOREIGN KEY FK_ACC652FB3ADB05F1');
        $this->addSql('ALTER TABLE option_list DROP FOREIGN KEY FK_ACC652FB1AD5CDBF');
        $this->addSql('ALTER TABLE option_list DROP FOREIGN KEY FK_ACC652FBCFFE9AD6');
        $this->addSql('DROP TABLE option_list');
    }
}
