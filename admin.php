<?php

$this->bindClass('Gallery\\Controller\\Gallery', '/galleries');

$this->on(
    'app.layout.init', function () {
        $this->helper('menus')->addLink(
            'modules', [
            'label'  => 'Galleries',
            'icon'   => 'galleries:icon.svg',
            'route'  => '/galleries',
            'active' => false,
            'group'  => 'Galleries',
            'prio'   => 3
            ]
        );
    }
);

$this->on(
    'app.layout.assets', function (array &$assets) {

        $assets[] = ['src' => 'content:assets/js/content.js', 'type' => 'module'];
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

