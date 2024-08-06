<?php

$this->bindClass('Gallery\\Controller\\Gallery', '/galleries');

$this->on(
    'app.layout.init', function () {
        $this->helper('menus')->addLink(
            'modules', [
            'label'  => 'Galleries',
            'icon'   => 'gallery:icon.svg',
            'route'  => '/galleries',
            'active' => false,
            'group'  => 'Content',
            'prio'   => 3
            ]
        );
    }
);

$this->on(
    'app.permissions.collect', function ($permissions) {

        $permissions['Content'] = [
        'component' => 'ContentModelSettings',
        'src' => 'content:assets/vue-components/content-model-permissions.js',
        'props' => [
          'models' => $this->module('content')->models()
        ]
        ];
    }
);

