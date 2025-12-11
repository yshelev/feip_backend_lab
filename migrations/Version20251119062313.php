<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119062313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request DROP CONSTRAINT fk_3b978f9f9d86650f');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT fk_3b978f9fa4a739af');
        $this->addSql('DROP INDEX idx_3b978f9f9d86650f');
        $this->addSql('DROP INDEX uniq_3b978f9fa4a739af');
        $this->addSql('ALTER TABLE request ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE request ADD house_id INT NOT NULL');
        $this->addSql('ALTER TABLE request DROP user_id_id');
        $this->addSql('ALTER TABLE request DROP house_id_id');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F6BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3B978F9FA76ED395 ON request (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B978F9F6BB74515 ON request (house_id)');
        $this->addSql('ALTER TABLE "user" ADD role VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE "user" SET role = \'ROLE_USER\' WHERE role IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER COLUMN role SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD password VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE "user" SET password = \'DEFAULT_PASSWORD\' WHERE password IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER COLUMN password SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP role');
        $this->addSql('ALTER TABLE "user" DROP password');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FA76ED395');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F6BB74515');
        $this->addSql('DROP INDEX IDX_3B978F9FA76ED395');
        $this->addSql('DROP INDEX UNIQ_3B978F9F6BB74515');
        $this->addSql('ALTER TABLE request ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE request ADD house_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE request DROP user_id');
        $this->addSql('ALTER TABLE request DROP house_id');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT fk_3b978f9f9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT fk_3b978f9fa4a739af FOREIGN KEY (house_id_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3b978f9f9d86650f ON request (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_3b978f9fa4a739af ON request (house_id_id)');
    }
}
