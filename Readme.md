## Installation

1. Run `composer require guiziweb/gemini-seo-plugin`

2. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
    Guiziweb\GeminiSeoPlugin\GuiziwebGeminiSeoPlugin::class => ['all' => true],
    Gemini\Symfony\GeminiBundle::class => ['all' => true],
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@GuiziwebGeminiSeoPlugin/Resources/config/app/config.yml" }

```

4. Add admin routes

```yml
# config/routes/sylius_admin.yaml
guiziweb_gemini_seo_plugin_admin:
  resource: '@GuiziwebGeminiSeoPlugin/Resources/config/admin_routing.yml'
  prefix: '/%sylius_admin.path_name%'
```



5.Create file templates/bundles/SyliusAdminBundle/Product/Tab/_details.html.twig

```twig
{{ include('@SyliusUi/_javascripts.html.twig', { 'path': 'bundles/guiziwebgeminiseoplugin/js/ai-button.js'}) }}
```

6.Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console assets:install
php bin/console cache:clear
```