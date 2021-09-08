<?php

namespace App\Controller;

use App\Model\User as ModelUser;
use Base\AbstractController;

class User extends AbstractController
{
  function loginAction()
  {
    echo __METHOD__;
  }

  function registerAction()
  {
    $user = new ModelUser();
    return $this->view->render('User/register.phtml', ['userName' => 'Eli', 'user' => $user]);
  }
}
