<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Guiziweb\GeminiSeoPlugin\Service\Prompt;
use Guiziweb\GeminiSeoPlugin\Repository\PromptRepository;
use Sylius\Component\Product\Model\ProductTranslation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GeminiController
{
    public function __construct(
        private Prompt $prompt,
        private EntityRepository $productTranslationRepository,
        private PromptRepository $promptRepository
    ) {
    }

    public function getAiResponse(Request $request): JsonResponse
    {
        $resourceId = $request->request->get('id');
        $resourceField = $request->request->get('field');
        $productTranslation = $this->productTranslationRepository->find($resourceId);

        if (!$productTranslation instanceof ProductTranslation) {
            return $this->createErrorResponse('Product translation not found.');
        }

        try {
            $description = $productTranslation->getDescription() ?? '';
            $locale = $productTranslation->getLocale() ?? '';

            return new JsonResponse([
                'data' => $this->generateAiResponseFromDescription($resourceField, $description, $locale),
            ]);
        } catch (\Exception $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    private function generateAiResponseFromDescription(string $resourceField, string $description, string $locale): string
    {
        $validFields = [
            Prompt::META_KEYWORDS,
            Prompt::SHORT_DESCRIPTION,
            Prompt::META_DESCRIPTION,
        ];

        if (!in_array($resourceField, $validFields, true)) {
            throw new \InvalidArgumentException("Invalid resource field: {$resourceField}");
        }

        $prompt = $this->promptRepository->findOneBy(['code' => $resourceField]);

        if (!$prompt) {
            throw new EntityNotFoundException("Prompt not found for code: {$resourceField}");
        }

        $text = str_replace(
            ['{description}', '{locale}'],
            [$description, $locale],
            $prompt->getText()
        );

        $structure = json_decode($prompt->getStructure(), true);
        if (!is_array($structure)) {
            throw new \InvalidArgumentException('Invalid structure format.');
        }

        $data = $this->prompt->generate($structure, $text);

        $data = json_decode($data, true, 512, \JSON_THROW_ON_ERROR);

        if ($resourceField === Prompt::META_KEYWORDS) {

            if (!is_array($data)) {
                throw new \InvalidArgumentException('Invalid keywords format.');
            }

            return implode(', ', $data);
        }

        return $data;
    }

    private function createErrorResponse(string $message): JsonResponse
    {
        return new JsonResponse(['error' => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
