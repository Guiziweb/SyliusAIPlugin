<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Guiziweb\GeminiSeoPlugin\Service\Prompt;

final class Version20240911164542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create prompts tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE guiziweb_prompt (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, structure JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql("INSERT INTO guiziweb_prompt (code, text, structure) VALUES 
            ('" . Prompt::META_KEYWORDS . "', 'À partir de la description et de la locale suivante, générez une liste de 6 mots-clé pertinents. Description : {description}. Locale : {locale}', JSON_OBJECT('type', 'array', 'items', JSON_OBJECT('type', 'string')))");

        $this->addSql("INSERT INTO guiziweb_prompt (code, text, structure) VALUES 
            ('" . Prompt::SHORT_DESCRIPTION . "', 'À partir de la description et de la locale suivante, générez une courte description. Description : {description}. Locale : {locale}', JSON_OBJECT('type', 'string'))");

        $this->addSql("INSERT INTO guiziweb_prompt (code, text, structure) VALUES 
            ('" . Prompt::META_DESCRIPTION . "', 'À partir de la description et de la locale suivante, générez une meta description. Description : {description}. Locale : {locale}', JSON_OBJECT('type', 'string'))");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE gemini_prompt');
    }
}
