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
}