<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904141949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
            CREATE TABLE cart (
                id VARCHAR(36) NOT NULL,
                user_id VARCHAR(36) NOT NULL,
                status VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
        SQL);

        $this->addSql(<<<SQL
            CREATE TABLE cart_item (
                id VARCHAR(36) NOT NULL,
                cart_id VARCHAR(36) NOT NULL,
                quantity INT NOT NULL,
                product_id VARCHAR(36) NOT NULL,
                product_description VARCHAR(50) NOT NULL,
                product_price_amount INT NOT NULL,
                product_price_currency VARCHAR(3) NOT NULL,
                INDEX IDX_F0FE25271AD5CDBF (cart_id),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
        SQL);

        $this->addSql(<<<SQL
            CREATE TABLE product (
                id VARCHAR(36) NOT NULL,
                description VARCHAR(50) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                price_amount INT NOT NULL,
                price_currency VARCHAR(3) NOT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
        SQL);

        $this->addSql(<<<SQL
            ALTER TABLE cart_item
            ADD CONSTRAINT FK_F0FE25271AD5CDBF
                FOREIGN KEY (cart_id)
                REFERENCES cart (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE product');
    }
}
