<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\EventListener;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    public function addCustomMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $child = $menu->getChild('configuration');

        if ($child instanceof ItemInterface) {
            $child->addChild('gemini', [
                    'route' => 'guiziweb_gemini_admin_prompt_index',
            ])
                ->setLabel('Gemini')
                ->setLabelAttribute('icon', 'star');
        }
    }
}
