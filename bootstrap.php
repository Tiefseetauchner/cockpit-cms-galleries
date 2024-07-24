<?php

$this->helpers['content'] = 'Content\\Helper\\Content';
$this->helpers['content.model'] = 'Content\\Helper\\Model';

$this->on(
    'app.admin.init', function () {
        include __DIR__.'/admin.php';
    }
);

// // load cli related code
// $this->on(
//     'app.cli.init', function ($cli) {
//         $app = $this;
//         include __DIR__.'/cli.php';
//     }
// );

// // load api request related code
// $this->on(
//     'app.api.request', function () {
//         include __DIR__.'/api.php';
//     }
// );

// content api
$this->module('gallery')->extend(
    [
    'createGallery' => function (string $name, array $data = []): mixed {
        return $this->app->helper('content.model')->create("gallery_".$name, $data);
    },

    'removeGallery' => function (string $name): bool {
        return $this->app->helper('content.model')->remove("gallery_".$name);
    },

    'galleries' => function (bool $extended = false): array {
        return array_filter(
            $this->app->helper('content.model')->models(), function ($model): bool {
                return str_starts_with($model['name'], 'gallery_');
            }
        );
    },

    'gallery' => function (string $name): mixed {
        return $this->app->helper('content.model')->model($name);
    },
    ]
);