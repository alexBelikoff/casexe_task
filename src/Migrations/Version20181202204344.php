<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181202204344 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA security');
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lottery_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prize_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prize_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prize_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE security.user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, city VARCHAR(64) NOT NULL, steet VARCHAR(128) NOT NULL, house INT NOT NULL, flat INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lottery (id INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, active BOOLEAN DEFAULT NULL, cash_total INT DEFAULT NULL, exchange_coefficient DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE prize (id INT NOT NULL, lottery_id INT NOT NULL, user_id INT DEFAULT NULL, prize_type_id INT NOT NULL, prize_item_id INT DEFAULT NULL, prize_sum INT DEFAULT NULL, prize_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, send_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, convert_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, reject_flag BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51C88BC1CFAA77DD ON prize (lottery_id)');
        $this->addSql('CREATE INDEX IDX_51C88BC1A76ED395 ON prize (user_id)');
        $this->addSql('CREATE INDEX IDX_51C88BC175D1EEED ON prize (prize_type_id)');
        $this->addSql('CREATE INDEX IDX_51C88BC1A2F23020 ON prize (prize_item_id)');
        $this->addSql('CREATE TABLE prize_item (id INT NOT NULL, lottery_id INT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_900F0EB9CFAA77DD ON prize_item (lottery_id)');
        $this->addSql('CREATE TABLE prize_type (id INT NOT NULL, lottery_id INT NOT NULL, name VARCHAR(64) NOT NULL, range_min INT DEFAULT NULL, range_max INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3CA7C8ECFAA77DD ON prize_type (lottery_id)');
        $this->addSql('CREATE TABLE security."user" (id INT NOT NULL, address_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, bank_account_num VARCHAR(64) NOT NULL, loyalty_points INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_963093F7F85E0677 ON security."user" (username)');
        $this->addSql('CREATE INDEX IDX_963093F7F5B7AF75 ON security."user" (address_id)');
        $this->addSql('ALTER TABLE prize ADD CONSTRAINT FK_51C88BC1CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lottery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prize ADD CONSTRAINT FK_51C88BC1A76ED395 FOREIGN KEY (user_id) REFERENCES security."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prize ADD CONSTRAINT FK_51C88BC175D1EEED FOREIGN KEY (prize_type_id) REFERENCES prize_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prize ADD CONSTRAINT FK_51C88BC1A2F23020 FOREIGN KEY (prize_item_id) REFERENCES prize_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prize_item ADD CONSTRAINT FK_900F0EB9CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lottery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prize_type ADD CONSTRAINT FK_3CA7C8ECFAA77DD FOREIGN KEY (lottery_id) REFERENCES lottery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE security."user" ADD CONSTRAINT FK_963093F7F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE security."user" DROP CONSTRAINT FK_963093F7F5B7AF75');
        $this->addSql('ALTER TABLE prize DROP CONSTRAINT FK_51C88BC1CFAA77DD');
        $this->addSql('ALTER TABLE prize_item DROP CONSTRAINT FK_900F0EB9CFAA77DD');
        $this->addSql('ALTER TABLE prize_type DROP CONSTRAINT FK_3CA7C8ECFAA77DD');
        $this->addSql('ALTER TABLE prize DROP CONSTRAINT FK_51C88BC1A2F23020');
        $this->addSql('ALTER TABLE prize DROP CONSTRAINT FK_51C88BC175D1EEED');
        $this->addSql('ALTER TABLE prize DROP CONSTRAINT FK_51C88BC1A76ED395');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lottery_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prize_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prize_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prize_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE security.user_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE lottery');
        $this->addSql('DROP TABLE prize');
        $this->addSql('DROP TABLE prize_item');
        $this->addSql('DROP TABLE prize_type');
        $this->addSql('DROP TABLE security."user"');
    }
}
