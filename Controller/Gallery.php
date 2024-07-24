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
}