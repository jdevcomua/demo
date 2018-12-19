<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Http\Requests\EmailTemplateRequest;
use App\Jobs\SendMassMails;
use App\Models\EmailTemplate;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailTemplatesController extends Controller
{

    public function index()
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
     * @param EmailTemplateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailTemplateRequest $request)
    {
        EmailTemplate::create($request->validated());

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Шаблон повідомленння успішно створено');
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
     * @param EmailTemplateRequest $request
     * @param  \App\Models\EmailTemplate $template
     * @return \Illuminate\Http\Response
     */
    public function update(EmailTemplateRequest $request, EmailTemplate $template)
    {
        $template->update($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Шаблон повідомленння успішно оновлено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailTemplate $template
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(EmailTemplate $template)
    {
        $template->delete();

        return redirect()
            ->back()
            ->with('success', 'Шаблон повідомленння було успішно видалено!');
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
