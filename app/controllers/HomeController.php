<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;

class HomeController{

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

   public function index(){
     $this->view->render('/site/pages/home/index');
   }


}