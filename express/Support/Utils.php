<?php

declare(strict_types=1);

namespace Fyyb\Support;

class Utils
{
  public static function clearURI(String $uri):String
  {
      for ($i=0; $i < substr_count($uri, '/'); $i++) { 
          $uri = str_replace('//', '/', $uri);
        };
        
        if(strlen($uri) > 1) {
            if (substr($uri, -1) === '/') {
                $uri = substr($uri,0,-1);
            }
        }
        
      return $uri;
  }
}