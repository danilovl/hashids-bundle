[![phpunit](https://github.com/danilovl/hashids-bundle/actions/workflows/phpunit.yml/badge.svg)](https://github.com/danilovl/hashids-bundle/actions/workflows/phpunit.yml)
[![downloads](https://img.shields.io/packagist/dt/danilovl/hashids-bundle)](https://packagist.org/packages/danilovl/hashids-bundle)
[![latest Stable Version](https://img.shields.io/packagist/v/danilovl/hashids-bundle)](https://packagist.org/packages/danilovl/hashids-bundle)
[![license](https://img.shields.io/packagist/l/danilovl/hashids-bundle)](https://packagist.org/packages/danilovl/hashids-bundle)

# HashidsBundle #

## About ##

Symfony bundle provides integrates hashids.

### Requirements

* PHP 8.3 or higher
* Symfony 6.3 or higher
* Hashids 4.1.0 or higher

### 1. Installation

Install `danilovl/hashids-bundle` package by Composer:

``` bash
$ composer require danilovl/hashids-bundle
```

Add the `HashidsBundle` to your application's bundles if does not add automatically:

``` php
<?php
// config/bundles.php

return [
    // ...
    Danilovl\HashidsBundle\HashidsBundle::class => ['all' => true]
];
```

### 2. Usage

Project parameters.

```yaml
# config/services.yaml

danilovl_hashids:
  salt: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
  min_hash_length: 20
  alphabet: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
  enable_param_converter: false 
```


#### 2.1 ParamConverter

HashidsBundle automatically provides decode hashids request parameters.

Routes.

```yaml
# config/routes.yaml

conversation_detail:
  path: /detail/{id}
  requirements:
    id: '^[a-zA-Z0-9]{10}$'
  defaults:
    _controller: App\Controller\ConversationController:detail
  methods: [GET, POST]
```

In controllers requirement `id` will be decoded and `ParamConverter` will try to find `Conversation` by id.

```php
<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ConversationController extends AbstractController
{
    public function detail(Request $request, Conversation $conversation): Response
    {
        return $this->render('conversation/detail.html.twig', [
            'conversation' => $conversation
        ]);
    }
}
```

Attribute if using `MapEntity` with specific keys in request.

```php
#[HashidsRequestConverterAttribute(requestAttributesKeys: ['id_work', 'id_task'])]
public function edit(
    Request $request,
    #[MapEntity(mapping: ['id_work' => 'id'])] Work $work,
    #[MapEntity(mapping: ['id_task' => 'id'])] Task $task
): Response {
    $this->denyAccessUnlessGranted(VoterSupportConstant::EDIT, $task);

    return $this->taskEditHandle->handle($request, $work, $task);
}
```

#### 2.2 Service

Get service `HashidsService::class` in controller.

```php
<?php declare(strict_types=1);

namespace App\Controller;

use Danilovl\HashidsBundle\Service\HashidsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function detail(Request $request): Response
    {
        $userId = $this->get(HashidsService::class)->decode($request->get('id'));
        if ($userId) {
            $userId = $this->get(HashidsService::class)->encode($request->get('id'));
        }

        return $this->render('profile/edit.html.twig', [
            'userId' => $userId
        ]);
    }
}
```

Simple DI integration.

```php
<?php declare(strict_types=1);

namespace App\Controller;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function __construct(private HashidsServiceInterface $hashidsService)
    {
    }

    public function detail(Request $request): Response
    {
        $userId = $this->hashidsService->decode($request->get('id'));
        if ($userId) {
            $userId = $this->hashidsService->encode($request->get('id'));
        }

        return $this->render('profile/edit.html.twig', [
            'userId' => $userId
        ]);
    }
}
```

#### 2.3 Twig extension

Hashids `encode` filter in templates.

```twig
   <a target="_blank"
      href="{{ path('user_detail', { 'id': user.id | hashids_encode }) }}"
      class="btn btn-primary btn-xs">
       <i class="fa fa-desktop"></i>
       {{ 'app.form.action.show_detail' | trans() }}
   </a>
```

Hashids `decode` filter in templates.

```twig
   <a target="_blank"
      href="{{ path('user_detail', { 'id': user.id | hashids_decode }) }}"
      class="btn btn-primary btn-xs">
       <i class="fa fa-desktop"></i>
       {{ 'app.form.action.show_detail' | trans() }}
   </a>
```

## License

The HashidsBundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
