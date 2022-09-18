<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220918213729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO user_profile
                (id, user_id, name, surname, phone)
            VALUES
                (1, 1, 'Gizem', 'Sağın', '5318852555'),
                (2, 2, 'Ayhan', 'Sağın', '5555555555'),
                (3, 3, 'Nebahat', 'Gün', '5555555554'),
                (4, 4, 'Berkay', 'Gün', '5555555553')
        ;");

        $this->addSql("INSERT INTO product
                (id, name, price)
            VALUES
                (1, 'Dizüstü Bilgisayar', 12000),
                (2, 'Klavye', 1000),
                (3, 'Mouse', 500)
        ;");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
