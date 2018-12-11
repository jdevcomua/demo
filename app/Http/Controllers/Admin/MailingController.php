<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use App\Models\Group;
use App\Models\MailingConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rafwell\Simplegrid\Grid;

class MailingController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.mailing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $templates = EmailTemplate::all();
        return view('admin.mailing.create', compact('groups', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();
        $validator = \Validator::make($data,[
            'group' => 'required',
            'email_template_id' => 'required|exists:email_templates,id',
            'type' => 'required|string',
        ]);

        $validator->sometimes('period_type', 'required|integer|min:1', function () use ($request) {
            return $request->get('type') === MailingConfig::TYPE_SCHEDULED;
        });
        $validator->sometimes('period', 'required|integer|min:1|max:' . count(MailingConfig::getMailingTypes()), function () use ($request) {
            return $request->get('type') === MailingConfig::TYPE_SCHEDULED;
        });

        $validator->sometimes('dates', 'required|string', function () use ($request) {
            return $request->get('type') === MailingConfig::TYPE_DATES;
        });

        $data['group_id'] = $data['group'] === 'all' ? null : $data['group'];
        $data['is_active'] = (isset($data['is_active']) && $data['is_active']) ? 1 : 0;
        unset($data['group']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        MailingConfig::create($data);

        return redirect()->route('admin.mailings.index')->with('message', 'Your template has been created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailingConfig  $mailing
     * @return \Illuminate\Http\Response
     */
    public function edit(MailingConfig $mailing)
    {
        $groups = Group::all();
        $templates = EmailTemplate::all();
        return view('admin.mailing.edit', compact('mailing','groups', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailingConfig  $mailing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailingConfig $mailing)
    {
        $data = $request->all();
        $validator = \Validator::make($data,[
            'group' => 'required',
            'email_template_id' => 'required|exists:email_templates,id',
            'type' => 'required|string',
        ]);

        $validator->sometimes('period_type', 'required|integer|min:1|max:' . count(MailingConfig::getMailingTypes()), function () use ($request) {
            return $request->get('type') === MailingConfig::TYPE_SCHEDULED;
        });
        $validator->sometimes('period', 'required|integer|min:1', function () use ($request) {
            return $request->get('type') === MailingConfig::TYPE_SCHEDULED;
        });

        $validator->sometimes('dates', 'required|string', function () use ($request) {
            return $request->get('type') === MailingConfig::TYPE_DATES;
        });

        $data['group_id'] = $data['group'] === 'all' ? null : $data['group'];
        $data['is_active'] = (isset($data['is_active']) && $data['is_active']) ? 1 : 0;

        unset($data['group']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $mailing->update($data);
        return redirect()->route('admin.mailings.index')->with(['message' => 'Your template has been updated successfuly']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailingConfig  $mailing
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailingConfig $mailing)
    {
        $mailing->delete();
        return redirect()->route('admin.mailings.index');
    }
}
