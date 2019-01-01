<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181230155420 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tmatch (id INT AUTO_INCREMENT NOT NULL, court_id INT DEFAULT NULL, tournament_id INT NOT NULL, player1_id INT DEFAULT NULL, player2_id INT DEFAULT NULL, winner_id INT DEFAULT NULL, score VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, INDEX IDX_9D08DC6CE3184009 (court_id), INDEX IDX_9D08DC6C33D1A3E7 (tournament_id), INDEX IDX_9D08DC6CC0990423 (player1_id), INDEX IDX_9D08DC6CD22CABCD (player2_id), INDEX IDX_9D08DC6C5DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tmatch ADD CONSTRAINT FK_9D08DC6CE3184009 FOREIGN KEY (court_id) REFERENCES court (id)');
        $this->addSql('ALTER TABLE tmatch ADD CONSTRAINT FK_9D08DC6C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tmatch ADD CONSTRAINT FK_9D08DC6CC0990423 FOREIGN KEY (player1_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE tmatch ADD CONSTRAINT FK_9D08DC6CD22CABCD FOREIGN KEY (player2_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE tmatch ADD CONSTRAINT FK_9D08DC6C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES player (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tmatch');
    }
}
