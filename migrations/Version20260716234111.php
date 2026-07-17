<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260716234111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changed invoiceFilename into invoiceFilenames JSON type to allow multiple files to be uploaded.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product (name)');
        $this->addSql('ALTER TABLE warehouse_operation CHANGE invoice_filename invoice_filenames JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D34A04AD5E237E06 ON product');
        $this->addSql('ALTER TABLE warehouse_operation CHANGE invoice_filenames invoice_filename JSON DEFAULT NULL');
    }
}
