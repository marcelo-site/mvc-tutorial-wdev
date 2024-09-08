<?php

namespace App\Utils;

function replaceSeparatorDirectory($path)
{
  return str_replace("/", DIRECTORY_SEPARATOR, $path);
}
