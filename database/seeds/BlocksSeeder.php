<?php

use Illuminate\Database\Seeder;

class BlocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blocks = [
            'about-page',
            'email.new-animal'
        ];

        foreach ($blocks as $block) {
            $temp = new \App\Models\Block();
            $temp->title = $block;
            if (strpos($block, 'email') !== false) {
                $block->subject = $block;
            }
            $block->body = $block;
            $block->save();
        }
    }
}
