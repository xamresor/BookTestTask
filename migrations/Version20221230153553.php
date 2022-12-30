<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230153553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE users ( 
                id int NOT NULL AUTO_INCREMENT, 
                name VARCHAR(20), 
                invited_by_user_id int, 
                PRIMARY KEY (id));
            CREATE INDEX index_invited_by_user_id ON users (invited_by_user_id);
        ");

        $this->addSql("        
            CREATE TABLE books ( 
                id int NOT NULL AUTO_INCREMENT, 
                user_id int, 
                title VARCHAR(20), 
                rating int, 
                PRIMARY KEY (id));
            CREATE INDEX index_user_id ON books (user_id);
        ");

        $this->addSql("    
            INSERT INTO users VALUES
            (1, 'Harry', null),
            (2, 'Hermiona', 1),
            (3, 'Ron', 1),
            (4, 'Snape', 2),
            (5, 'Dumbledore', 3),
            (6, 'Dobby', 3),
            (7, 'Volandemort', 6),
            (8, 'Hat', 2);
        ");

        $this->addSql("     
            INSERT INTO books
                (`user_id`, `title`, `rating`)
            VALUES
                (1, 'Aberto', 50),
                (1, 'Accio', 12),
                (2, 'False memory spell', 43),
                (2, 'Ferula', 65),
                (3, 'Fianto Duri', 13),
                (3, 'Magicus Extremos', 99),
                (4, 'Nox', 87),
                (4, 'Nebulus', -52),
                (4, 'Oculus Reparo', 65),
                (5, 'Obliviate', 14),
                (6, 'Obscuro', 125),
                (7, 'Salvio hexia', -23),
                (7, 'Sardine Hex', -5),
                (7, 'Deletrius', 5),
                (7, 'Densaugeo', 20),
                (8, 'Deprimo', 80);
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS users');
        $this->addSql('DROP TABLE IF EXISTS books');
    }
}
