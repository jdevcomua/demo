<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class SiteController extends Controller
{

    public function __invoke()
    {
        if (\Auth::user() !== null) {
            return redirect()->route('animals.index');
        } else {
            return redirect()->route('about');
        }
    }

    public function faq()
    {
        return view('faq', [
            'questions' => Faq::all()
        ]);
    }
    
    public function test()
    {
        $block = \App\Models\Block::where('title', 'email.request-accepted')->first();
        if (!$block) {
            $block = new \App\Models\Block();
            $block->title = 'email.request-accepted';
            $block->subject = 'Ваш запит на тваринку було схвалено!';
            $block->body = 'Ваш запит на тваринку було схвалено!';
            $block->save();
        }

        $block = \App\Models\Block::where('title', 'email.request-cancelled')->first();
        if (!$block) {
            $block = new \App\Models\Block();
            $block->title = 'email.request-cancelled';
            $block->subject = 'Ваш запит на тваринку було відхилено!';
            $block->body = 'Ваш запит на тваринку було відхилено!';
            $block->save();
        }

        return redirect('/');
    }

}
