<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151009104521 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('CREATE TABLE page_tag (page_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_CF36BF12C4663E4 (page_id), INDEX IDX_CF36BF12BAD26311 (tag_id), PRIMARY KEY(page_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE page_tag ADD CONSTRAINT FK_CF36BF12C4663E4 FOREIGN KEY (page_id) REFERENCES `page` (id)');
		$this->addSql('ALTER TABLE page_tag ADD CONSTRAINT FK_CF36BF12BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('DROP TABLE page_tag');
	}
}
