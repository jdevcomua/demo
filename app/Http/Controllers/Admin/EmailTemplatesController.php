<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Jobs\SendEmail;
use App\Jobs\SendMassMails;
use App\Models\EmailTemplate;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rafwell\Simplegrid\Grid;

class EmailTemplatesController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.templates.index');
    }

    public function templatesData(Request $request)
    {
        $model = new EmailTemplate();
        $query = $model->newQuery();

        $response = DataTables::provide($request, $model, $query, null);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->only('name','subject', 'body');
        $validator = \Validator::make($data,[
            'name' => 'required|min:4',
            'subject' => 'required|min:4',
            'body' => 'required|min:4'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        EmailTemplate::create($data);

        return redirect()->route('admin.templates.index')->with('message', 'Your template has been created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailTemplate  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailTemplate  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailTemplate $template)
    {
        $data = $request->only('subject', 'body');
        $validator = \Validator::make($data,[
            'subject' => 'required|min:4',
            'body' => 'required|min:4'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $template->update($data);
        return redirect()->route('admin.templates.index')->with(['message' => 'Your template has been updated successfuly']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailTemplate  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index');
    }

    public function showFirings()
    {
        $templates = EmailTemplate::all();
        $users = User::all();
        return view('admin.templates.fire', compact('templates', 'users'));
    }

    public function fireTemplate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'template' => 'required|exists:email_templates,id',
        ], [
            'template.required' => 'Будь ласка оберіть тип листа',
            'users' => 'Будь ласка оберіть користивачів',
        ]);

        $validator->sometimes('users', 'array', function ($input) {
            return !!$input['users'];
        });

        $validator->sometimes('users.*', 'exists:users,id', function ($input) {
            return !!$input['users'];
        });

        if($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }

        if ($request->get('users')) {
            $users = User::whereIn('id', $request->get('users'))->get();
        } else {
            $users = User::all();
        }

        $template = EmailTemplate::find($request->get('template'));

        SendMassMails::dispatch($users, $template)->delay(now()->addSeconds(1))
            ->onQueue('default');

        return redirect()
            ->back()
            ->with('success', 'Розсилку створено та поставлено в чергу');
    }

}
