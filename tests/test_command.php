<?php

namespace robots\tests;
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../robots/Command.php";
use PHPUnit\Framework\TestCase;
use robots\Command;

class CommandTest extends TestCase {

    // test fail situations
    public function test_execute_fail() {
        $block_to_pos = [0, 1, 2];
        $positions = [[0], [1], [2]];
        $this->assertFalse((new Command("xxxx 1 yyyy 2"))->execute($block_to_pos, $positions));
        $this->assertFalse((new Command("move 1 onto 1"))->execute($block_to_pos, $positions));
        $block_to_pos = [0, 2, 2];
        $positions = [[0], [], [1, 2]];
        $this->assertFalse((new Command("pile 1 over 2"))->execute($block_to_pos, $positions));
    }

    // test move onto
    public function test_move_onto() {
        // no block to put back
        $block_to_pos = [0, 1, 2, 3];
        $positions = [[0], [1], [2], [3]];
        $this->assertTrue((new Command("move 1 onto 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 2, 2, 3]); // 1block in the postion 2
        $this->assertTrue($positions === [[0], [], [2, 1], [3]]);
        // src has more
        $block_to_pos = [0, 2, 2, 3];
        $positions = [[0], [], [2, 1], [3]];
        $this->assertTrue((new Command("move 2 onto 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 1, 0, 3]);
        $this->assertTrue($positions === [[0, 2], [1], [], [3]]);
        // dest has more
        $block_to_pos = [0, 1, 0, 3];
        $positions = [[0, 2], [1], [], [3]];
        $this->assertTrue((new Command("move 1 onto 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 0, 2, 3]);
        $this->assertTrue($positions === [[0, 1], [], [2], [3]]);
        // both src and dest has more
        $block_to_pos = [0, 0, 2, 2];
        $positions = [[0, 1], [], [2, 3], []];
        $this->assertTrue((new Command("move 0 onto 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [2, 1, 2, 3]);
        $this->assertTrue($positions === [[], [1], [2, 0], [3]]);
    }

    // test move over
    public function test_move_over() {
        // no block to put back
        $block_to_pos = [0, 1, 2, 3];
        $positions = [[0], [1], [2], [3]];
        $this->assertTrue((new Command("move 1 over 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 2, 2, 3]);
        $this->assertTrue($positions === [[0], [], [2, 1], [3]]);
        // src has more
        $block_to_pos = [0, 2, 2, 3];
        $positions = [[0], [], [2, 1], [3]];
        $this->assertTrue((new Command("move 2 over 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 1, 0, 3]);
        $this->assertTrue($positions === [[0, 2], [1], [], [3]]);
        // dest has more
        $block_to_pos = [0, 1, 0, 3];
        $positions = [[0, 2], [1], [], [3]];
        $this->assertTrue((new Command("move 1 over 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 0, 0, 3]);
        $this->assertTrue($positions === [[0, 2, 1], [], [], [3]]);
        // both src and dest has more
        $block_to_pos = [0, 0, 2, 2];
        $positions = [[0, 1], [], [2, 3], []];
        $this->assertTrue((new Command("move 0 over 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [2, 1, 2, 2]);
        $this->assertTrue($positions === [[], [1], [2, 3, 0], []]);
    }

    // test pile onto
    public function test_pile_onto() {
        // no block to put back
        $block_to_pos = [0, 1, 2, 3];
        $positions = [[0], [1], [2], [3]];
        $this->assertTrue((new Command("pile 1 onto 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 2, 2, 3]);
        $this->assertTrue($positions === [[0], [], [2, 1], [3]]);
        // src has more
        $block_to_pos = [0, 2, 2, 2];
        $positions = [[0], [], [2, 1, 3], []];
        $this->assertTrue((new Command("pile 1 onto 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 0, 2, 0]);
        $this->assertTrue($positions === [[0, 1, 3], [], [2], []]);
        // dest has more
        $block_to_pos = [0, 1, 0, 1];
        $positions = [[0, 2], [1, 3], [], []];
        $this->assertTrue((new Command("pile 1 onto 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 0, 2, 0]);
        $this->assertTrue($positions === [[0, 1, 3], [], [2], []]);
        // both src and dest has more
        $block_to_pos = [0, 0, 2, 2];
        $positions = [[0, 1], [], [2, 3], []];
        $this->assertTrue((new Command("pile 0 onto 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [2, 2, 2, 3]);
        $this->assertTrue($positions === [[], [], [2, 0, 1], [3]]);
    }

    // test pile over
    public function test_pile_over() {
        // no block to put back
        $block_to_pos = [0, 1, 2, 3];
        $positions = [[0], [1], [2], [3]];
        $this->assertTrue((new Command("pile 1 over 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 2, 2, 3]);
        $this->assertTrue($positions === [[0], [], [2, 1], [3]]);
        // src has more
        $block_to_pos = [0, 2, 2, 2];
        $positions = [[0], [], [2, 1, 3], []];
        $this->assertTrue((new Command("pile 1 over 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 0, 2, 0]);
        $this->assertTrue($positions === [[0, 1, 3], [], [2], []]);
        // dest has more
        $block_to_pos = [0, 1, 0, 3];
        $positions = [[0, 2], [1], [], [3]];
        $this->assertTrue((new Command("pile 1 over 0"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [0, 0, 0, 3]);
        $this->assertTrue($positions === [[0, 2, 1], [], [], [3]]);
        // both src and dest has more
        $block_to_pos = [0, 0, 2, 2];
        $positions = [[0, 1], [], [2, 3], []];
        $this->assertTrue((new Command("pile 0 over 2"))->execute($block_to_pos, $positions));
        $this->assertTrue($block_to_pos === [2, 2, 2, 2]);
        $this->assertTrue($positions === [[], [], [2, 3, 0, 1], []]);
    }

}