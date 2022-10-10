<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010073511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses ADD user_id INT NOT NULL, ADD categ_adress_id INT NOT NULL');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516C81FC54 FOREIGN KEY (categ_adress_id) REFERENCES categ_adress (id)');
        $this->addSql('CREATE INDEX IDX_6FCA7516A76ED395 ON addresses (user_id)');
        $this->addSql('CREATE INDEX IDX_6FCA7516C81FC54 ON addresses (categ_adress_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA7516A76ED395');
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA7516C81FC54');
        $this->addSql('DROP INDEX IDX_6FCA7516A76ED395 ON addresses');
        $this->addSql('DROP INDEX IDX_6FCA7516C81FC54 ON addresses');
        $this->addSql('ALTER TABLE addresses DROP user_id, DROP categ_adress_id');
    }
}
