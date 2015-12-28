<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151225162420 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('CREATE TABLE `file` (id INT AUTO_INCREMENT NOT NULL, year VARCHAR(255) NOT NULL, month VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, checksum VARCHAR(255) NOT NULL, joints INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8C9F36105E237E06 (name), UNIQUE INDEX UNIQ_8C9F3610DE6FDF9A (checksum), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE video ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE wiki ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE article ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE image ADD file_id INT DEFAULT NULL AFTER id, ADD is_active TINYINT(1) NOT NULL AFTER name, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP alt, DROP file');
		$this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F93CB796C FOREIGN KEY (file_id) REFERENCES `file` (id)');
		$this->addSql('CREATE INDEX IDX_C53D045F93CB796C ON image (file_id)');
		$this->addSql('ALTER TABLE user ADD avatar_id INT DEFAULT NULL AFTER id, ADD password VARCHAR(60) DEFAULT NULL, ADD salt VARCHAR(10) DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP avatar');
		$this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES `file` (id)');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498FFBE0F7 ON user (salt)');
		$this->addSql('CREATE INDEX IDX_8D93D64986383B10 ON user (avatar_id)');
		$this->addSql('ALTER TABLE tag ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F93CB796C');
		$this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
		$this->addSql('DROP TABLE `file`');
		$this->addSql('ALTER TABLE `article` DROP created_at, DROP updated_at');
		$this->addSql('DROP INDEX IDX_C53D045F93CB796C ON `image`');
		$this->addSql('ALTER TABLE `image` ADD alt VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD file VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP file_id, DROP is_active, DROP created_at, DROP updated_at');
		$this->addSql('ALTER TABLE `tag` DROP created_at, DROP updated_at');
		$this->addSql('DROP INDEX UNIQ_8D93D6498FFBE0F7 ON `user`');
		$this->addSql('DROP INDEX IDX_8D93D64986383B10 ON `user`');
		$this->addSql('ALTER TABLE `user` ADD avatar VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP avatar_id, DROP password, DROP salt, DROP created_at, DROP updated_at');
		$this->addSql('ALTER TABLE `video` DROP created_at, DROP updated_at');
		$this->addSql('ALTER TABLE `wiki` DROP created_at, DROP updated_at');
	}
}
