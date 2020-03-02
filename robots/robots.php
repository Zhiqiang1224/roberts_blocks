<?php

namespace robots;
require_once __DIR__ . "/command.php";
use robots\Command;


// check arg count
// if ($argc != 2) {
//     die ("unexpected arg count: {$argc}");
// }

// // check if input file exists
// $inputs = $argv[1];
// if (!file_exists($inputs)) {
//     die("input file {$inputs} not exists");
// }

// open file to read
$file = fopen("D:\\Xampp\\htdocs\\robotsproj\\robots\\input.txt", "r");
if (!$file) {
    die("cannot open file {$inputs}");
}

// init boxes
$box_count = (int) fgets($file);
for ($i=0; $i < $box_count; $i++) { 
    $box_to_pos[$i] = [$i];
    //$positions[$i] = [$i];
}

// execute commands
while (!feof($file) && ($command = trim(fgets($file))) != "quit") {
    (new Command($command))->execute($box_to_pos);
}

// close file
fclose($file);

// print result
foreach ($box_to_pos as $pos => $pos_boxes) {
    echo $pos . ":" . rtrim(" " . implode(" ", $pos_boxes)) . "\n";
}
