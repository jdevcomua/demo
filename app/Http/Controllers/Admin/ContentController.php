<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ContentController extends Controller
{

    const ABOUT_PAGE = 'about-page';
    const ANIMAL_VERIFY = 'animal-verify';

    public function faqIndex()
    {
        return view('admin.info.content_faq');
    }

    public function faqData(Request $request)
    {
        $response = DataTables::provide($request, new Faq());

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function faqDelete($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
        return redirect()
            ->back()
            ->with('success_faq', 'Питання було успішно видалено!');
    }

    public function faqStore(Request $request)
    {
        $data = $request->only(['question', 'answer']);
        $validator = \Validator::make($data, [
            'question' => 'required|string|max:1000',
            'answer' => 'required|string'
        ], [
            'question.required' => 'Питання є обов\'язковим полем',
            'question.max' => 'Питання має бути менше :max символів',
            'answer.required' => 'Відповідь є обов\'язковим полем'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'new_faq')
                ->withInput();
        }

        Faq::create($data);

        return redirect()
            ->back()
            ->with('success_new_faq', 'Питання було успішно додано!');
    }


    public function blockIndex()
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

            $blocks = new Collection();
            $blocks->merge($block, $block2);
        }

        return view('admin.info.content_block', [
            'blocks' => $blocks
        ]);
    }

    public function blockUpdate(Request $request, $id)
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

        return redirect()
            ->back()
            ->with('success_block', 'Сторінку було успішно оновлено');
    }
}
