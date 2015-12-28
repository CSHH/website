<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151227130227 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('DROP TABLE article_tag');
		$this->addSql('DROP TABLE image_tag');
		$this->addSql('DROP TABLE video_tag');
		$this->addSql('DROP TABLE wiki_tag');
		$this->addSql('ALTER TABLE video ADD tag_id INT DEFAULT NULL AFTER id');
		$this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CBAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
		$this->addSql('CREATE INDEX IDX_7CC7DA2CBAD26311 ON video (tag_id)');
		$this->addSql('ALTER TABLE wiki ADD tag_id INT DEFAULT NULL AFTER id');
		$this->addSql('ALTER TABLE wiki ADD CONSTRAINT FK_22CDDC06BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
		$this->addSql('CREATE INDEX IDX_22CDDC06BAD26311 ON wiki (tag_id)');
		$this->addSql('ALTER TABLE article ADD tag_id INT DEFAULT NULL AFTER id');
		$this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
		$this->addSql('CREATE INDEX IDX_23A0E66BAD26311 ON article (tag_id)');
		$this->addSql('ALTER TABLE image ADD tag_id INT DEFAULT NULL AFTER id');
		$this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
		$this->addSql('CREATE INDEX IDX_C53D045FBAD26311 ON image (tag_id)');
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('CREATE TABLE article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_919694F97294869C (article_id), INDEX IDX_919694F9BAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE image_tag (image_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5B6367D03DA5256D (image_id), INDEX IDX_5B6367D0BAD26311 (tag_id), PRIMARY KEY(image_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE video_tag (video_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F910728729C1004E (video_id), INDEX IDX_F9107287BAD26311 (tag_id), PRIMARY KEY(video_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE wiki_tag (wiki_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_442ED516AA948DBE (wiki_id), INDEX IDX_442ED516BAD26311 (tag_id), PRIMARY KEY(wiki_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
		$this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F97294869C FOREIGN KEY (article_id) REFERENCES article (id)');
		$this->addSql('ALTER TABLE image_tag ADD CONSTRAINT FK_5B6367D0BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
		$this->addSql('ALTER TABLE image_tag ADD CONSTRAINT FK_5B6367D03DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
		$this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F9107287BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
		$this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F910728729C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
		$this->addSql('ALTER TABLE wiki_tag ADD CONSTRAINT FK_442ED516BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
		$this->addSql('ALTER TABLE wiki_tag ADD CONSTRAINT FK_442ED516AA948DBE FOREIGN KEY (wiki_id) REFERENCES wiki (id)');
		$this->addSql('ALTER TABLE `article` DROP FOREIGN KEY FK_23A0E66BAD26311');
		$this->addSql('DROP INDEX IDX_23A0E66BAD26311 ON `article`');
		$this->addSql('ALTER TABLE `article` DROP tag_id');
		$this->addSql('ALTER TABLE `image` DROP FOREIGN KEY FK_C53D045FBAD26311');
		$this->addSql('DROP INDEX IDX_C53D045FBAD26311 ON `image`');
		$this->addSql('ALTER TABLE `image` DROP tag_id');
		$this->addSql('ALTER TABLE `video` DROP FOREIGN KEY FK_7CC7DA2CBAD26311');
		$this->addSql('DROP INDEX IDX_7CC7DA2CBAD26311 ON `video`');
		$this->addSql('ALTER TABLE `video` DROP tag_id');
		$this->addSql('ALTER TABLE `wiki` DROP FOREIGN KEY FK_22CDDC06BAD26311');
		$this->addSql('DROP INDEX IDX_22CDDC06BAD26311 ON `wiki`');
		$this->addSql('ALTER TABLE `wiki` DROP tag_id');
	}
}
