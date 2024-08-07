## Prerequisites

Assurez-vous que votre environnement de développement répond aux exigences suivantes :

-  [PHP 8.1+](https://php.net/releases/)
-  Un VPN aux etats-Unis

## Setup for dev

1. Run `composer install`.

3. Run `make init`.

4. Run `cd tests/Application && symfony server:start`.


## Installation sur un autre Sylius

Ajouter les routes: 

```yaml
#tests/Application/config/routes/sylius_admin.yaml

acseo_sylius_gemini_seo_plugin:
  resource: "@AcseoGeminiSeoPlugin/config/routes/admin.yml"
```

S'assurer que le bundle est chargé 

```php
#tests/Application/config/bundles.php
return [
    Acseo\GeminiSeoPlugin\AcseoGeminiSeoPlugin::class => ['all' => true],
];
```

Override translationForm template ou copier 'templates/SyliusAdminBundle' dans votre dossier de template:

vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/Resources/views/Macro/translationForm.html.twig

```twig
{% for field in translationForm %}
    {% if field.vars.name != 'slug' %}

        {% if field.vars.name == 'metaKeywords' %}

            {% include '@AcseoGeminiSeoPlugin/_gemini_keywords.html.twig' %}

        {% endif %}
        {{ form_row(field) }}
    {% else %}
        {% include slugFieldTemplate with { 'slugField': translationForm.slug, 'resource': resource } %}
    {% endif %}
{% endfor %}
```

Override _details.html.twig template ou copier 'templates/SyliusAdminBundle' dans votre dossier de template:

#vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/Resources/views/Product/Tab/_details.html.twig

```twig
{{ include('@SyliusUi/_javascripts.html.twig', {'path': 'bundles/acseogeminiseoplugin/js/test.js'}) }}
```


## Trucs à regarder :

https://github.com/google-gemini-php/symfony enlever ce plugin et utiliser directement lui

https://github.com/google-gemini-php/client

Voir la différence avec celui là 

https://github.com/gemini-api-php/client

Celui la à une MR ouverte pour gerer le fait que Gemini réponde directement en JSON (Plus simple pour gerer du contenu structuré)

https://github.com/gemini-api-php/client/issues/31

la doc : 

https://ai.google.dev/gemini-api/docs/json-mode?hl=fr&authuser=1&lang=web



