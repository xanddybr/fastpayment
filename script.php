<?php
$values = ['nome'=>'monica'];
$fields = implode(",", array_keys($values));
echo $fields;