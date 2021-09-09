<?php

namespace App\Controller;

use Base\AbstractController;
use App\Model\User;

class Login extends AbstractController
{
  function index()
  {
    return $this->view->render(
      'login.phtml',
      [
        'title' => 'Registration',
      ]
    );
  }

  public function register()
  {
    $name = (string) $_POST['name'];
    $email = (string) $_POST['email'];
    $password = (string) $_POST['password'];
    $passwordConfirm = (string) $_POST['passwordConfirm'];

    if (!$name || !$password) {
      return 'All fields are required';
    }

    if ($password !== $passwordConfirm) {
      return 'Passwords do not match';
    }

    if (mb_strlen($password) < 5) {
      return 'The password is less than 5 characters';
    }

    $user = new User($name, $email, $password, date('Y-m-d H:i:s'));
    $user->save();

    return 'You have successfully registered';
  }
}
