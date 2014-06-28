<?php
function autoload($classe)
{
  require '../'.str_replace('\\', '/', $classe).'.class.php';
}
 
spl_autoload_register('autoload');