<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402085809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sliders (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, name VARCHAR(50) NOT NULL, position SMALLINT NOT NULL, created DATETIME NOT NULL, modified DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_85A59DB84584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sliders ADD CONSTRAINT FK_85A59DB84584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE administrator CHANGE roles roles JSON NOT NULL, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE categories CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE discount_codes CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE flavours CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE login_history CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ordered_products CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE orders CHANGE discount_code_id discount_code_id INT DEFAULT NULL, CHANGE company company VARCHAR(50) DEFAULT NULL, CHANGE house house VARCHAR(5) DEFAULT NULL, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE products CHANGE url url VARCHAR(50) NOT NULL, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE subpages CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE variables CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE modified modified DATETIME DEFAULT NULL, CHANGE deleted deleted DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sliders');
        $this->addSql('ALTER TABLE administrator CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE categories CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE discount_codes CHANGE created created DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE flavours CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE images CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE login_history CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE ordered_products CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE orders CHANGE discount_code_id discount_code_id INT DEFAULT NULL, CHANGE company company VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8_general_ci, CHANGE house house VARCHAR(5) DEFAULT \'NULL\' COLLATE utf8_general_ci, CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE products CHANGE url url VARCHAR(51) NOT NULL COLLATE utf8_general_ci, CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE subpages CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE variables CHANGE created created DATETIME DEFAULT \'current_timestamp()\', CHANGE modified modified DATETIME DEFAULT \'NULL\', CHANGE deleted deleted DATETIME DEFAULT \'NULL\'');
    }
}
