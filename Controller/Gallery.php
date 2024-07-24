<?php

namespace Gallery\Controller;

use App\Controller\App;
use ArrayObject;

class Gallery extends App
{
    public function index()
    {
        $this->helper('theme')->favicon('gallery:icon.svg');

        return $this->render('gallery:views/index.php');
    }

    public function create()
    {
        if (!$this->isAllowed('gallery/:galleries/manage')) {
            return $this->stop(401);
        }

        $model = [
          'name' => '',
          'label' => '',
          'info' => "This is a predefined gallery model by the gallery addon.\n" .
                    "You can change this if required, like adding values, " .
                    "but it might break the gallery addon if you change the Gallery Images field.",
          'type' => 'collection',
          'group' => '',
          'color' => null,
          'revisions' => false,
          'fields' => [
            [
              'name' => 'images',
              'type' => 'asset',
              'label' => 'Gallery Images',
              'info' => 'DO NOT CHANGE THIS FIELD! Internal field for the gallery addon.',
              'group' => '',
              'i18n' => false,
              'required' => true,
              'multiple' => false,
              'meta' => [],
              'opts' => []
            ]
          ],
          'preview' => [],
          'meta' => null
        ];

        $isUpdate = false;

        $this->helper('theme')->favicon('content:icon.svg');

        return $this->render('gallery:views/create.php', compact('model', 'isUpdate'));
    }

    public function edit()
    {
        if (!$this->isAllowed('gallery/:galleries/manage')) {
            return $this->stop(401);
        }

        $model = [
          'name' => '',
          'label' => '',
          'type' => 'collection',
          'group' => '',
          'color' => null,
          'revisions' => false,
          'fields' => [],
          'preview' => [],
          'meta' => null
        ];

        $isUpdate = false;

        $this->helper('theme')->favicon('content:icon.svg');

        return $this->render('gallery:views/create.php', compact('model'));
    }

    public function items($model = null)
    {

        if (!$model) {
            return false;
        }

        $model = $this->module('content')->model($model);

        if (!$model || $model['type'] != 'collection' || ! str_starts_with($model['name'], 'gallery')) {
            return $this->stop(404);
        }

        if (!$this->isAllowed("content/{$model['name']}/read")) {
            return $this->stop(401);
        }

        $fields = $model['fields'];

        $locales = $this->helper('locales')->locales();

        if (count($locales) == 1) {
            $locales = [];
        } else {
            $locales[0]['visible'] = true;
        }

        $this->helper('theme')->favicon(isset($model['icon']) && $model['icon'] ? $model['icon'] : 'content:assets/icons/collection.svg', $model['color'] ?? '#000');

        return $this->render('gallery:views/items.php', compact('model', 'fields', 'locales'));
    }

}