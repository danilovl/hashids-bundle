# HashidsBundle #

## About ##

Symfony bundle provides integrates hashids.

### Requirements

* PHP 8.0.0 or higher
* Symfony 5.0 or higher
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
  continue_next_converter: false 
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

#### 2.2 Service

Get service `danilovl_hashids` in controller.

```php
<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function detail(Request $request): Response
    {
        $userId = $this->get('danilovl_hashids')->decode($request->get('id'));
        if ($userId) {
            $userId = $this->get('danilovl_hashids')->encode($request->get('id'));
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
      href="{{ path('user_detail', { 'id': user.id | hashids_encode  }) }}"
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