<?php

namespace App\Http\Controllers\Admin\Content;

use App\Helpers\DataTables;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.content.faq');
    }

    public function data(Request $request)
    {
        $response = DataTables::provide($request, new Faq());

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function store(FaqRequest $request)
    {
        $maxOrder = Faq::all()
            ->sortByDesc('order')
            ->first();
        $maxOrder = $maxOrder ? $maxOrder->order + 1 : 1;

        $data = $request->validated();
        $data['order'] = $maxOrder;

        Faq::create($data);
        \Cache::tags('faq')->flush();

        return redirect()
            ->back()
            ->with('success_new_faq', 'Питання було успішно додано!');
    }

    public function show($id)
    {
        $faq = Faq::findOrFail($id);

        return response()->json([
            'question' => $faq->question,
            'answer' => $faq->answer
        ]);
    }

    public function update(FaqRequest $request, $id)
    {
        $data = $request->validated();
        $faq = Faq::findOrFail($id);

        $faq->update($data);
        \Cache::tags('faq')->flush();

        return response()->json([
            'message' => 'Питання було успішно оновлено!'
        ]);
    }

    public function destroy(Faq $model, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        $model->normalizeOrder();
        \Cache::tags('faq')->flush();

        return redirect()
            ->back()
            ->with('success_faq', 'Питання було успішно видалено!');
    }

    public function moveUp($id)
    {
        $faq = Faq::findOrFail($id);

        $faq->moveOrderUp();
        \Cache::tags('faq')->flush();

        return redirect()
            ->back()
            ->with('success_faq', 'Порядок питань було успішно змінено!');
    }

    public function moveDown($id)
    {
        $faq = Faq::findOrFail($id);

        $faq->moveOrderDown();
        \Cache::tags('faq')->flush();

        return redirect()
            ->back()
            ->with('success_faq', 'Порядок питань було успішно змінено!');
    }
}
