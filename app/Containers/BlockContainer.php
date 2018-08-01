<?php

namespace App\Containers;

use App\Models\Block;

class BlockContainer
{
    protected $block_name;

    public function get($block_name = '') {
        $block = Block::where('title', $block_name)->first();
        if ($block) {
            return $block->body;
        }
        return 'Block: ' . $block_name;
    }
}