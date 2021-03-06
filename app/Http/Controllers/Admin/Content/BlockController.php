<?php

namespace App\Http\Controllers\Admin\Content;

use App\Helpers\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    const ABOUT_PAGE = 'about-page';
    const ANIMAL_VERIFY = 'animal-verify';
    const AGREEMENT_PAGE = 'agreement-page';
    const RULES_PAGE = 'rules-page';

    public function index()
    {
        $blocks = Block::whereNull('subject')->get();

        if (!$blocks->count()) {
            $block = new Block([
                'title' => self::ABOUT_PAGE,
                'body' => ''
            ]);
            $block->save();

            $block2 = new Block([
                'title' => self::ANIMAL_VERIFY,
                'body' => ''
            ]);
            $block2->save();

            $block3 = new Block([
                'title' => self::AGREEMENT_PAGE,
                'body' => ''
            ]);
            $block3->save();

            $block4 = new Block([
                'title' => self::RULES_PAGE,
                'body' => ''
            ]);
            $block4->save();

            $blocks = new Collection();
            $blocks->merge($block, $block2, $block3, $block4);

            \Cache::tags('blocks')->flush();
        }

        return view('admin.content.block', [
            'blocks' => $blocks
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'body' => 'required|string'
        ], [
            'body.required' => 'Текст сторінки є обов\'язковим'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'block')
                ->withInput();
        }

        $block = Block::findOrFail($id);
        $block->body = $request->get('body');
        $block->save();
        \Cache::tags('blocks')->flush();

        return redirect()
            ->back()
            ->with('success_block', 'Сторінку було успішно оновлено');
    }
}
