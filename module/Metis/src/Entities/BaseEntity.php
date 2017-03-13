<?php

namespace Metis\Entities;

class BaseEntity
{
  /**
  * used for zend form binding
  */
    public function getArrayCopy()
  {
      return get_object_vars($this);
  }
}
