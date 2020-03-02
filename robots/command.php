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
    public function execute(array &$block_to_pos, array &$positions) {
        if (!in_array($this->action, Command::ACTIONS)) {
            return false;
        }
        if ($block_to_pos[$this->block_a] == $block_to_pos[$this->block_b]) {
            return false;
        }
        $this->{$this->action}($block_to_pos, $positions);
        return true;
    }

    
     /**
     * implement move onto
     * @param $block_to_pos
     * @param $positions
     * @return array
     */
    private function move_onto(array &$block_to_pos, array &$positions) {
        $this->put_back_upon($block_to_pos, $positions, $this->block_b); // pop all the blocks above b
        $this->move_over($block_to_pos, $positions);   // pop all the blocks above a and push a to b
    }

    /**
     * implement move over
     * @param $block_to_pos
     * @param $positions
     * @return array
     */
    private function move_over(array &$block_to_pos, array &$positions) {
        // pop all the blocks above a
        $this->put_back_upon($block_to_pos, $positions, $this->block_a);  
        // push a to b 
        array_pop($positions[$block_to_pos[$this->block_a]]);
        array_push($positions[$block_to_pos[$this->block_b]], $this->block_a);
        // update the index array 
        $block_to_pos[$this->block_a] = $block_to_pos[$this->block_b];
    }

     /**
     * implement pile onto
     * @param $block_to_pos
     * @param $positions
     * @return array
     */
    private function pile_onto(array &$block_to_pos, array &$positions) {
        $this->put_back_upon($block_to_pos, $positions, $this->block_b);
        $this->pile_over($block_to_pos, $positions);
    }

     /**
     * implement pile over
     * @param $block_to_pos
     * @param $positions
     * @return array
     */
    private function pile_over(array &$block_to_pos, array &$positions) {
         // find the block a index in the sub array of position array
        $block_a_pos = $block_to_pos[$this->block_a];
        $pos_blocks = &$positions[$block_a_pos];
        $block_a_pos_index = array_search($this->block_a, $pos_blocks);
         // loop push the all the blocks above the block b which including itself
        while (count($pos_blocks) > $block_a_pos_index) {
            $block_i = array_splice($pos_blocks, $block_a_pos_index, 1)[0];
            array_push($positions[$block_to_pos[$this->block_b]], $block_i);
            $block_to_pos[$block_i] = $block_to_pos[$this->block_b];
        }
    }

     /**
     * put block upon given one back to initial position
     * @param $block_to_pos
     * @param $positions
     * @param $block_i
     */
    private function put_back_upon(array &$block_to_pos, array &$positions, int $block_i) {
        // find the block i index in the sub array of position array
        $block_i_pos = $block_to_pos[$block_i];
        $pos_blocks = &$positions[$block_i_pos];
        $block_i_pos_index = array_search($block_i, $pos_blocks);
        // loop remove the all the blocks above the block i 
        while (count($pos_blocks) > $block_i_pos_index + 1) {
            $block_back = array_pop($pos_blocks);
            array_push($positions[$block_back], $block_back); // back to init position in the position array
            $block_to_pos[$block_back] = $block_back;         // back to init index in index array
        }
    }

}