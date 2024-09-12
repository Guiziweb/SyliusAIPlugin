<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\Service;

use Gemini\Client;
use Gemini\Data\GenerationConfig;

class Prompt
{
    public const META_KEYWORDS = 'metaKeywords';

    public const META_DESCRIPTION = 'metaDescription';

    public const SHORT_DESCRIPTION = 'shortDescription';

    private Client $gemini;

    public function __construct(Client $gemini)
    {
        $this->gemini = $gemini;
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

        return $this->gemini
         ->generativeModel('models/gemini-1.5-pro')
         ->withGenerationConfig($generationConfig)
         ->generateContent($prompt)
         ->text();
    }
}
