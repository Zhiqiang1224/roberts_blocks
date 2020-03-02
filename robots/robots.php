<?php

namespace robots;
require_once __DIR__ . "/command.php";
use robots\Command;


check arg count
if ($argc != 2) {
    die ("unexpected arg count: {$argc}");
}

// check if input file exists
$inputs = $argv[1];
if (!file_exists($inputs)) {
    die("input file {$inputs} not exists");
}

// open file to read
$file = fopen($input, "r");
if (!$file) {
    die("cannot open file {$inputs}");
}

// init blocks
$block_count = (int) fgets($file);
for ($i=0; $i < $block_count; $i++) { 
    $block_to_pos[$i] = [$i];
    $positions[$i] = [$i];
}

// execute commands
while (!feof($file) && ($command = trim(fgets($file))) != "quit") {
    (new Command($command))->execute($block_to_pos);
}

// close file
fclose($file);

// print result
foreach ($block_to_pos as $pos => $pos_blocks) {
    echo $pos . ":" . rtrim(" " . implode(" ", $pos_blocks)) . "\n";
}
