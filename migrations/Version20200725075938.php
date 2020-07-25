<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200725075938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE song_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE song (id INT NOT NULL, name VARCHAR(255) NOT NULL, singer VARCHAR(255) NOT NULL, year INT NOT NULL, duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE hazardous_asteroids');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE comment');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE song_id_seq CASCADE');
        $this->addSql('DROP TABLE song');
    }
}
