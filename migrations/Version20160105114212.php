<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160105114212 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE video ADD url VARCHAR(255) DEFAULT NULL AFTER slug, ADD src VARCHAR(255) DEFAULT NULL AFTER url, DROP youtube_video_src, DROP youtube_video_url, DROP vimeo_video_src, DROP vimeo_video_url');
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE `video` ADD youtube_video_src VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD youtube_video_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD vimeo_video_src VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD vimeo_video_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP url, DROP src');
	}
}
