<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151225135353 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery_image DROP FOREIGN KEY FK_21A0D47C4E7AF8F');
        $this->addSql('ALTER TABLE page_tag DROP FOREIGN KEY FK_CF36BF12C4663E4');
        $this->addSql('CREATE TABLE `wiki_draft` (id INT AUTO_INCREMENT NOT NULL, wiki_id INT DEFAULT NULL, user_id INT DEFAULT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E9F6AF18AA948DBE (wiki_id), INDEX IDX_E9F6AF18A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `video` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, src VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_7CC7DA2C5E237E06 (name), UNIQUE INDEX UNIQ_7CC7DA2C989D9B62 (slug), INDEX IDX_7CC7DA2CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_tag (video_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F910728729C1004E (video_id), INDEX IDX_F9107287BAD26311 (tag_id), PRIMARY KEY(video_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `wiki` (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, last_updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, perex VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_22CDDC065E237E06 (name), UNIQUE INDEX UNIQ_22CDDC06989D9B62 (slug), INDEX IDX_22CDDC06B03A8386 (created_by_id), INDEX IDX_22CDDC06E562D849 (last_updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wiki_tag (wiki_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_442ED516AA948DBE (wiki_id), INDEX IDX_442ED516BAD26311 (tag_id), PRIMARY KEY(wiki_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wiki_related (wiki_id INT NOT NULL, related_wiki_id INT NOT NULL, INDEX IDX_A8648055AA948DBE (wiki_id), INDEX IDX_A8648055958D471B (related_wiki_id), PRIMARY KEY(wiki_id, related_wiki_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wiki_contributor (wiki_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4FECD5C5AA948DBE (wiki_id), INDEX IDX_4FECD5C5A76ED395 (user_id), PRIMARY KEY(wiki_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `article` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, perex VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_23A0E665E237E06 (name), UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), INDEX IDX_23A0E66A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_919694F97294869C (article_id), INDEX IDX_919694F9BAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_tag (image_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5B6367D03DA5256D (image_id), INDEX IDX_5B6367D0BAD26311 (tag_id), PRIMARY KEY(image_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `wiki_draft` ADD CONSTRAINT FK_E9F6AF18AA948DBE FOREIGN KEY (wiki_id) REFERENCES `wiki` (id)');
        $this->addSql('ALTER TABLE `wiki_draft` ADD CONSTRAINT FK_E9F6AF18A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `video` ADD CONSTRAINT FK_7CC7DA2CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F910728729C1004E FOREIGN KEY (video_id) REFERENCES `video` (id)');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F9107287BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
        $this->addSql('ALTER TABLE `wiki` ADD CONSTRAINT FK_22CDDC06B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `wiki` ADD CONSTRAINT FK_22CDDC06E562D849 FOREIGN KEY (last_updated_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE wiki_tag ADD CONSTRAINT FK_442ED516AA948DBE FOREIGN KEY (wiki_id) REFERENCES `wiki` (id)');
        $this->addSql('ALTER TABLE wiki_tag ADD CONSTRAINT FK_442ED516BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
        $this->addSql('ALTER TABLE wiki_related ADD CONSTRAINT FK_A8648055AA948DBE FOREIGN KEY (wiki_id) REFERENCES `wiki` (id)');
        $this->addSql('ALTER TABLE wiki_related ADD CONSTRAINT FK_A8648055958D471B FOREIGN KEY (related_wiki_id) REFERENCES `wiki` (id)');
        $this->addSql('ALTER TABLE wiki_contributor ADD CONSTRAINT FK_4FECD5C5AA948DBE FOREIGN KEY (wiki_id) REFERENCES `wiki` (id)');
        $this->addSql('ALTER TABLE wiki_contributor ADD CONSTRAINT FK_4FECD5C5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `article` ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F97294869C FOREIGN KEY (article_id) REFERENCES `article` (id)');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
        $this->addSql('ALTER TABLE image_tag ADD CONSTRAINT FK_5B6367D03DA5256D FOREIGN KEY (image_id) REFERENCES `image` (id)');
        $this->addSql('ALTER TABLE image_tag ADD CONSTRAINT FK_5B6367D0BAD26311 FOREIGN KEY (tag_id) REFERENCES `tag` (id)');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_image');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_tag');
        $this->addSql('ALTER TABLE tag ADD slug VARCHAR(255) NOT NULL, DROP is_deletable');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783989D9B62 ON tag (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE video_tag DROP FOREIGN KEY FK_F910728729C1004E');
        $this->addSql('ALTER TABLE wiki_draft DROP FOREIGN KEY FK_E9F6AF18AA948DBE');
        $this->addSql('ALTER TABLE wiki_tag DROP FOREIGN KEY FK_442ED516AA948DBE');
        $this->addSql('ALTER TABLE wiki_related DROP FOREIGN KEY FK_A8648055AA948DBE');
        $this->addSql('ALTER TABLE wiki_related DROP FOREIGN KEY FK_A8648055958D471B');
        $this->addSql('ALTER TABLE wiki_contributor DROP FOREIGN KEY FK_4FECD5C5AA948DBE');
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F97294869C');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_472B783A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_image (gallery_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_21A0D47C4E7AF8F (gallery_id), INDEX IDX_21A0D47C3DA5256D (image_id), PRIMARY KEY(gallery_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, text LONGTEXT NOT NULL COLLATE utf8_unicode_ci, title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, keywords VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_140AB6205E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_tag (page_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_CF36BF12C4663E4 (page_id), INDEX IDX_CF36BF12BAD26311 (tag_id), PRIMARY KEY(page_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery_image ADD CONSTRAINT FK_21A0D47C3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE gallery_image ADD CONSTRAINT FK_21A0D47C4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE page_tag ADD CONSTRAINT FK_CF36BF12BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE page_tag ADD CONSTRAINT FK_CF36BF12C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('DROP TABLE `wiki_draft`');
        $this->addSql('DROP TABLE `video`');
        $this->addSql('DROP TABLE video_tag');
        $this->addSql('DROP TABLE `wiki`');
        $this->addSql('DROP TABLE wiki_tag');
        $this->addSql('DROP TABLE wiki_related');
        $this->addSql('DROP TABLE wiki_contributor');
        $this->addSql('DROP TABLE `article`');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('DROP TABLE image_tag');
        $this->addSql('DROP INDEX UNIQ_389B783989D9B62 ON `tag`');
        $this->addSql('ALTER TABLE `tag` ADD is_deletable TINYINT(1) NOT NULL, DROP slug');
    }
}
