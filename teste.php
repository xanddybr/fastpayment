
<?php

class Person {
   
   public int $id;
   public string $name;

   public function __construct() {
    $this->name = "Alexandre";
   }

   public function soma($v1, $v2){
     return $v1 * $v2;
   }

}

$user = new Person();
echo $user->soma(357,687);