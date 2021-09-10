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
        'title' => 'Login Page',
        'user' => $this->getUser(),
      ]
    );
  }

  public function auth()
  {
    $email = (string) $_POST['email'];
    $password = (string) $_POST['password'];

    $user = User::getByEmail($email);

    if (!$user) {
      return 'Incorrect email or password';
    }

    if ($user->getPassword() !== User::getPasswordHash($password)) {
      return 'Incorrect email or password';
    }

    $this->session->authUser($user->getId());
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

    if (!$email) {
      return 'Email is required';
    }

    if ($password !== $passwordConfirm) {
      return 'Passwords do not match';
    }

    if (mb_strlen($password) < 5) {
      return 'The password is less than 5 characters';
    }

    $userData = [
      'name' => $name,
      'email' => $email,
      'password' => $password,
      'created_at' => date('Y-m-d H:i:s')
    ];

    $user = new User($userData);
    $user->save();

    return 'You have successfully registered';
  }
}
