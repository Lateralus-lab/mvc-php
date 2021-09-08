<?php

namespace Base;

abstract class AbstractController
{
  /** @var View */
  protected $view;

  protected function redirect(string $url)
  {
    throw new RedirectException($url);
  }

  public function setView(View $view): void
  {
    $this->view = $view;
  }
}
