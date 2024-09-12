<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\Form\Type;

use Guiziweb\GeminiSeoPlugin\Entity\Prompt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Text',
            ])
            ->add('structure', TextType::class, [
                'label' => 'Structure',
            ])
            ->add('code', TextType::class, [
                'label' => 'Code',
                'attr' => ['readonly' => 'readonly'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prompt::class,
        ]);
    }
}
