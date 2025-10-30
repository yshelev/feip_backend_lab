<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251024141853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request ADD house_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE request DROP phone_number');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FA4A739AF FOREIGN KEY (house_id_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B978F9FA4A739AF ON request (house_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FA4A739AF');
        $this->addSql('DROP INDEX UNIQ_3B978F9FA4A739AF');
        $this->addSql('ALTER TABLE request ADD phone_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE request DROP house_id_id');
    }
}
