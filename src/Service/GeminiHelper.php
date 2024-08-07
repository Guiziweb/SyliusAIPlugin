<?php

declare(strict_types=1);

namespace Acseo\GeminiSeoPlugin\Service;

use Gemini\Data\GenerationConfig;

final class GeminiHelper
{
    private const PROMPT_KEYWORD_TEMPLATE = <<<PROMPT
À partir du titre de produit suivant et de la description fournie, 
et dans la langue fournie, génere 5 méta mots clés pertinents et différents les uns des autres, 
qui peuvent être utilisés pour améliorer le référencement du produit. 

Titre du produit : %s
Description : %s
Langue : %s
PROMPT;


    public function generateKeywords(string $title, string $description, string $lang)
    {
        $client = \Gemini::factory()
            ->withApiKey('clé api')
            ->withBaseUrl('https://generativelanguage.googleapis.com/v1beta/') // default: https://generativelanguage.googleapis.com/v1/
            ->withHttpHeader('X-My-Header', 'foo')
            ->withQueryParam('my-param', 'bar')
            ->withHttpClient(new \GuzzleHttp\Client([]))
            ->make();


        $prompt = sprintf(self::PROMPT_KEYWORD_TEMPLATE, $title, $description, $lang);

        $generationConfig = new GenerationConfig(
            1,
            [],
            null,
            null,
            null,
            null,
            'application/json',
            [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'product_name' => [
                            'type' => 'string'
                        ]
                    ]
                ]
             ],

        );

        $generativeModel = $client
            ->generativeModel('models/gemini-1.5-pro')
            ->withGenerationConfig($generationConfig)
            ->generateContent("Give me 5 name for my product, it's a bottle of tea");

        dump($generativeModel);
        dump($generativeModel->toArray());
        dump($generativeModel->text());
        return $generativeModel->toArray();

    }
}
