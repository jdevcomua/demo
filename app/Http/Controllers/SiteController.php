<?php

namespace App\Http\Controllers;

use App\Events\BadgeScanned;
use App\Models\Animal;
use App\Models\Faq;
use App\Models\IdentifyingDevice;
use App\Models\IdentifyingDeviceType;
use Cache;
use Illuminate\Http\Request;

class SiteController extends Controller
{

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
        $badgeTypeId = IdentifyingDeviceType::where('name', 'Жетон')->firstOrFail()->id;
        $badgeDevice = IdentifyingDevice::where('identifying_device_type_id', $badgeTypeId)
            ->where('number', $request->get('n'))
            ->first();

        $animal = null;

        if ($badgeDevice) {
            $animal = $badgeDevice->animal;
        }

        if ($animal) {
            event(new BadgeScanned($animal->user, [$animal]));

            $user = $request->user();

            return ($user && $user->can('admin-panel'))
                ? redirect()->route('admin.db.animals.edit', $animal->id)
                : view('animals.show_contacts_owner', compact('animal'));
        }

        return redirect()->route('index', ['badgeNotFound' => 'show']);//Todo: ты уверен что нужно возвращать этот кусок GET-запросом?
    }

    public function acceptAgreement()
    {
        $user = \Auth::user();

        if ($user) {
            $user->terms_accepted = 1;
            $user->save();
            return \Response::json(['message' => 'Success']);
        }
        return \Response::json(['message' => 'Bad request'], 400);
    }

    public function declineAgreement()
    {
        if (\Auth::user()) {
            \Auth::logout();
            return redirect()->route('index');
        }
    }

}
