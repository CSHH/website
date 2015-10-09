<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151009111120 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('CREATE TABLE `gallery` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_472B783A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE gallery_image (gallery_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_21A0D47C4E7AF8F (gallery_id), INDEX IDX_21A0D47C3DA5256D (image_id), PRIMARY KEY(gallery_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE `image` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, file VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C53D045F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE gallery_image ADD CONSTRAINT FK_21A0D47C4E7AF8F FOREIGN KEY (gallery_id) REFERENCES `gallery` (id)');
		$this->addSql('ALTER TABLE gallery_image ADD CONSTRAINT FK_21A0D47C3DA5256D FOREIGN KEY (image_id) REFERENCES `image` (id)');
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE gallery_image DROP FOREIGN KEY FK_21A0D47C4E7AF8F');
		$this->addSql('ALTER TABLE gallery_image DROP FOREIGN KEY FK_21A0D47C3DA5256D');
		$this->addSql('DROP TABLE `gallery`');
		$this->addSql('DROP TABLE gallery_image');
		$this->addSql('DROP TABLE `image`');
	}
}
