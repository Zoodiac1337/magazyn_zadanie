<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260713194405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added product, warehouse, warehouse_user, and warehouse_operation tables with relationships and constraints.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE warehouse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE warehouse_user (warehouse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_52F9D69D5080ECDE (warehouse_id), INDEX IDX_52F9D69DA76ED395 (user_id), PRIMARY KEY (warehouse_id, user_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE warehouse_operation (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, type VARCHAR(10) NOT NULL, vat INT DEFAULT NULL, price_netto NUMERIC(10, 2) DEFAULT NULL, invoice_filename JSON DEFAULT NULL, created_at DATETIME NOT NULL, warehouse_id INT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2614E8D25080ECDE (warehouse_id), INDEX IDX_2614E8D24584665A (product_id), INDEX IDX_2614E8D2A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE warehouse_user ADD CONSTRAINT FK_52F9D69D5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE warehouse_user ADD CONSTRAINT FK_52F9D69DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE warehouse_operation ADD CONSTRAINT FK_2614E8D25080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE warehouse_operation ADD CONSTRAINT FK_2614E8D24584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE warehouse_operation ADD CONSTRAINT FK_2614E8D2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_user DROP FOREIGN KEY FK_52F9D69D5080ECDE');
        $this->addSql('ALTER TABLE warehouse_user DROP FOREIGN KEY FK_52F9D69DA76ED395');
        $this->addSql('ALTER TABLE warehouse_operation DROP FOREIGN KEY FK_2614E8D25080ECDE');
        $this->addSql('ALTER TABLE warehouse_operation DROP FOREIGN KEY FK_2614E8D24584665A');
        $this->addSql('ALTER TABLE warehouse_operation DROP FOREIGN KEY FK_2614E8D2A76ED395');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE warehouse');
        $this->addSql('DROP TABLE warehouse_user');
        $this->addSql('DROP TABLE warehouse_operation');
    }
}
