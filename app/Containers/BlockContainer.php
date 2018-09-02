<?php

namespace App\Containers;

use App\Models\Block;
use Cache;

class BlockContainer
{
    protected $block_name;

    public function get($block_name = '') {
        $block = Cache::tags('blocks')
            ->remember('block.'.$block_name, 1000, function() use ($block_name)
        {
            return Block::where('title', $block_name)->first();
        });
        if ($block) return $block->body;
        return 'Block: ' . $block_name;
    }
}