<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220918200647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO user
                (id, email, password, roles)
            VALUES
                (1, 'abc@case.com', '$2y$13\$ZTQaebFtlgUAfejyELwaqOJkS3dyEpkDMN2XFxpGCIx5TJBpHSUW.', '" . json_encode(['ROLE_ADMIN']) . "'),
                (2, 'user1@case.com', '$2y$13\$H0IvWLz1MrRUt6N8F0vC8e9tIkOdWMo2ljQh1/6vHPzheDKR0QAF2', '" . json_encode([]) . "'),
                (3, 'user2@case.com', '$2y$13\$ZRbX/KfmLzOIk3nbBPlKVexv3.cI/U99hpXU9F/6BgtMFs/K4Cl5C', '" . json_encode([]) . "'),
                (4, 'user3@case.com', '$2y$13\$ZTQaebFtlgUAfejyELwaqOJkS3dyEpkDMN2XFxpGCIx5TJBpHSUW.', '" . json_encode([]) . "')
        ;");
    }

    public function down(Schema $schema): void
    {
    }
}
