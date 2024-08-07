<?php


declare(strict_types=1);

namespace Acseo\GeminiSeoPlugin\Controller;

use Acseo\GeminiSeoPlugin\Service\GeminiHelper;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Component\Core\Model\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GeminiController
{
    public function __construct(
        private GeminiHelper $gemini,
        private ProductRepository $productRepository,
    ) {
    }

    public function ajaxAction(Request $request): Response
    {
        $resourceId = $request->request->get('id');
        $product = $this->productRepository->find($resourceId);

        if (!$product instanceof Product) {
            return $this->createErrorResponse('Produit non trouvé.', Response::HTTP_NOT_FOUND);
        }

        try {
            $data = $this->gemini->generateKeywords($product->getName(), $product->getDescription(), 'fr');
            return new JsonResponse(['data' => $data]);
        } catch (\Exception $e) {

            return $this->createErrorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function createErrorResponse(string $message, int $status): JsonResponse
    {
        return new JsonResponse(['error' => $message], $status);
    }
}
