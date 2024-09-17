<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\Service;

use Gemini\Data\GenerationConfig;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Prompt
{
    public const META_KEYWORDS = 'metaKeywords';

    public const META_DESCRIPTION = 'metaDescription';

    public const SHORT_DESCRIPTION = 'shortDescription';

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function generate(array $stucture, string $prompt): string
    {
        $generationConfig = new GenerationConfig(
            1,
            [],
            null,
            null,
            null,
            null,
            'application/json',
            $stucture,
        );

        $client = \Gemini::client($this->apiKey);


        return $client
            ->generativeModel('models/gemini-1.5-pro')
            ->withGenerationConfig($generationConfig)
            ->generateContent($prompt)
            ->text();
    }
}
