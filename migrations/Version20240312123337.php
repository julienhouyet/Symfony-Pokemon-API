<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240312123337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, owned_by_id INT NOT NULL, expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', token VARCHAR(68) NOT NULL, scopes JSON NOT NULL, INDEX IDX_7BA2F5EB5E70BCD7 (owned_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE move (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, power INT DEFAULT NULL, pp INT DEFAULT NULL, accuracy INT DEFAULT NULL, INDEX IDX_EF3E3778C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, pokedex_number INT NOT NULL, name VARCHAR(255) NOT NULL, height DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION NOT NULL, base_experience INT NOT NULL, UNIQUE INDEX UNIQ_62DC90F32996FAF8 (pokedex_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_move (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, move_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_D397493B2FE71C3E (pokemon_id), INDEX IDX_D397493B6DC541A8 (move_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_stat (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, stat_id INT NOT NULL, base_stat INT NOT NULL, effort INT NOT NULL, INDEX IDX_1C1181622FE71C3E (pokemon_id), INDEX IDX_1C1181629502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_type (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, type_id INT NOT NULL, slot INT NOT NULL, INDEX IDX_B077296A2FE71C3E (pokemon_id), INDEX IDX_B077296AC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EB5E70BCD7 FOREIGN KEY (owned_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E3778C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B6DC541A8 FOREIGN KEY (move_id) REFERENCES move (id)');
        $this->addSql('ALTER TABLE pokemon_stat ADD CONSTRAINT FK_1C1181622FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_stat ADD CONSTRAINT FK_1C1181629502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296A2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EB5E70BCD7');
        $this->addSql('ALTER TABLE move DROP FOREIGN KEY FK_EF3E3778C54C8C93');
        $this->addSql('ALTER TABLE pokemon_move DROP FOREIGN KEY FK_D397493B2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_move DROP FOREIGN KEY FK_D397493B6DC541A8');
        $this->addSql('ALTER TABLE pokemon_stat DROP FOREIGN KEY FK_1C1181622FE71C3E');
        $this->addSql('ALTER TABLE pokemon_stat DROP FOREIGN KEY FK_1C1181629502F0B');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296A2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296AC54C8C93');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_move');
        $this->addSql('DROP TABLE pokemon_stat');
        $this->addSql('DROP TABLE pokemon_type');
        $this->addSql('DROP TABLE stat');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
    }
}
