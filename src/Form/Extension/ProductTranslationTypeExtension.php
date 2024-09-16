<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\Form\Extension;

use Sylius\Bundle\ProductBundle\Form\Type\ProductTranslationType;
use Sylius\Component\Core\Model\ProductTranslation;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;

final class ProductTranslationTypeExtension extends AbstractTypeExtension
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
                'label' => 'sylius.form.product.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'sylius.form.product.slug',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'sylius.form.product.description',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $productTranslation = $event->getData();

            if (!$productTranslation instanceof ProductTranslation) {
                return;
            }

            $fields = [
                'metaKeywords',
                'metaDescription',
                'shortDescription',
            ];

            foreach ($fields as $field) {
                $form->add($field, TextType::class, [
                    'required' => false,
                    'label' => sprintf(
                        '<div class="ai-%s" data-resource-field="%s" data-translation-id="%d" data-url="%s">%s <i class="magic icon"></i></div>',
                        $field,
                        $field,
                        $productTranslation->getId(),
                        $this->router->generate('guiziweb_gemini_admin_ajax_ai_response'),
                        $field,
                    ),
                    'label_html' => true,
                ]);
            }
        });
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductTranslationType::class];
    }
}
