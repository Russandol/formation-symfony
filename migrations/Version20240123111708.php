<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123111708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts ADD fk_user_id INT DEFAULT NULL, ADD fk_team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAD943E582 FOREIGN KEY (fk_team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_885DBAFA5741EEB9 ON posts (fk_user_id)');
        $this->addSql('CREATE INDEX IDX_885DBAFAD943E582 ON posts (fk_team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA5741EEB9');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAD943E582');
        $this->addSql('DROP INDEX IDX_885DBAFA5741EEB9 ON posts');
        $this->addSql('DROP INDEX IDX_885DBAFAD943E582 ON posts');
        $this->addSql('ALTER TABLE posts DROP fk_user_id, DROP fk_team_id');
    }
}
