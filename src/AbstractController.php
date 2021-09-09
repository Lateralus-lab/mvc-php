<?php

namespace Base;

abstract class AbstractController
{
  /** @var View */
  protected $view;

  public function setView(View $view): void
  {
    $this->view = $view;
  }
}
