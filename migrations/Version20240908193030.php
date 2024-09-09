<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908193030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist_musique (playlist_id INT NOT NULL, musique_id INT NOT NULL, INDEX IDX_512241A66BBD148 (playlist_id), INDEX IDX_512241A625E254A1 (musique_id), PRIMARY KEY(playlist_id, musique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist_musique ADD CONSTRAINT FK_512241A66BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_musique ADD CONSTRAINT FK_512241A625E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC6BBD148');
        $this->addSql('DROP INDEX IDX_EE1D56BC6BBD148 ON musique');
        $this->addSql('ALTER TABLE musique DROP playlist_id, DROP artiste, DROP album, DROP duree, DROP youtube_url');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_musique DROP FOREIGN KEY FK_512241A66BBD148');
        $this->addSql('ALTER TABLE playlist_musique DROP FOREIGN KEY FK_512241A625E254A1');
        $this->addSql('DROP TABLE playlist_musique');
        $this->addSql('ALTER TABLE musique ADD playlist_id INT DEFAULT NULL, ADD artiste VARCHAR(255) NOT NULL, ADD album VARCHAR(255) NOT NULL, ADD duree INT NOT NULL, ADD youtube_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
        $this->addSql('CREATE INDEX IDX_EE1D56BC6BBD148 ON musique (playlist_id)');
    }
}
