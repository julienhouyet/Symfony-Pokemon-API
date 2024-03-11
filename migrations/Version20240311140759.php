<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311140759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_stat (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, stat_id INT DEFAULT NULL, base_stat INT NOT NULL, effort INT NOT NULL, INDEX IDX_1C1181622FE71C3E (pokemon_id), INDEX IDX_1C1181629502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_stat ADD CONSTRAINT FK_1C1181622FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_stat ADD CONSTRAINT FK_1C1181629502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_stat DROP FOREIGN KEY FK_1C1181622FE71C3E');
        $this->addSql('ALTER TABLE pokemon_stat DROP FOREIGN KEY FK_1C1181629502F0B');
        $this->addSql('DROP TABLE pokemon_stat');
        $this->addSql('DROP TABLE stat');
    }
}
