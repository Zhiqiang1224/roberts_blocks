<?php

namespace robots;

class Command {

    const ACTIONS = ["move_onto", "move_over", "pile_onto", "pile_over"];

    private $action;
    private $block_a;
    private $block_b;

    public function __construct($command) {
        $args = explode(" ", $command);
        if (count($args) == 4) {
            $this->action = $args[0] . "_" . $args[2];
            $this->block_a = (int) $args[1];
            $this->block_b = (int) $args[3];
        }
    }

    // execute command
    public function execute(&$block_to_pos, &$positions) {
        if (!in_array($this->action, Command::ACTIONS)) {
            return false;
        }
        if ($block_to_pos[$this->block_a] == $block_to_pos[$this->block_b]) {
            return false;
        }
        $this->{$this->action}($block_to_pos, $positions);
        return true;
    }

    // implement move onto
    private function move_onto(&$block_to_pos, &$positions) {
        $this->put_back_upon($block_to_pos, $positions, $this->block_b); // empty the b 
        $this->move_over($block_to_pos, $positions);
    }

    // implement move over
    private function move_over(&$block_to_pos, &$positions) {
        $this->put_back_upon($block_to_pos, $positions, $this->block_a);
        array_pop($positions[$block_to_pos[$this->block_a]]);
        array_push($positions[$block_to_pos[$this->block_b]], $this->block_a);
        $block_to_pos[$this->block_a] = $block_to_pos[$this->block_b];
    }

    // implement pile onto
    private function pile_onto(&$block_to_pos, &$positions) {
        $this->put_back_upon($block_to_pos, $positions, $this->block_b);
        $this->pile_over($block_to_pos, $positions);
    }

    // implement pile over
    private function pile_over(&$block_to_pos, &$positions) {
        $block_a_pos = $block_to_pos[$this->block_a];
        $pos_blocks = &$positions[$block_a_pos];
        $block_a_pos_index = array_search($this->block_a, $pos_blocks);
        while (count($pos_blocks) > $block_a_pos_index) {
            $block_i = array_splice($pos_blocks, $block_a_pos_index, 1)[0];
            array_push($positions[$block_to_pos[$this->block_b]], $block_i);
            $block_to_pos[$block_i] = $block_to_pos[$this->block_b];
        }
    }

    // put box upon given one back to initial position
    private function put_back_upon(&$block_to_pos, &$positions, $block_i) {
        $block_i_pos = $block_to_pos[$block_i];
        $pos_boxes = &$positions[$block_i_pos];
        $block_i_pos_index = array_search($block_i, $pos_boxes);
        while (count($pos_boxes) > $block_i_pos_index + 1) {
            $block_back = array_pop($pos_boxes);
            array_push($positions[$block_back], $block_back);
            $block_to_pos[$block_back] = $block_back;
        }
    }

}