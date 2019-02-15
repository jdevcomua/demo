<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Faq;
use Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $faqs = Cache::tags('faq')
            ->remember('faq', 1000, function()
        {
            return Faq::all()->sortBy('order');
        });
        return view('faq', [
            'questions' => $faqs
        ]);
    }

    public function badgeData(Request $request)
    {
        $animal = Animal::where('badge', '=', $request->get('n'))->first();

        if ($animal) {
            $user = $request->user();

            return ($user && $user->can('admin-panel'))
                ? redirect()->route('admin.db.animals.edit', $animal->id)
                : view('animals.show_contacts_owner', compact('animal'));
        }

        return redirect()->route('about', ['badgeNotFound' => 'show']);
    }

}
