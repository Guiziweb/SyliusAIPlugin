<?php

declare(strict_types=1);

namespace Acseo\GeminiSeoPlugin\Service;

use Gemini\Client;

final class GeminiHelper
{
    private const PROMPT_KEYWORD_TEMPLATE = <<<PROMPT
À partir du titre de produit suivant et de la description fournie, 
et dans la langue fournie, générez 5 méta mots clés pertinents et différents les uns des autres, 
qui peuvent être utilisés pour améliorer le référencement du produit. 
Mettez vos 5 mots les uns à la suite des autres, séparés par un espace.
Titre du produit : %s
Description : %s
Langue : %s
PROMPT;


    public function generateKeywords(string $title, string $description, string $lang): string
    {
        $client = \Gemini::client('clé api');

        $prompt = sprintf(self::PROMPT_KEYWORD_TEMPLATE, $title, $description, $lang);

        return $client->geminiPro()->generateContent($prompt)->text();

    }
}
