<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogsController extends Controller
{
    public function index()
    {
        return view('admin.logs.index');
    }

    public function data(Request $request)
    {
        $model = new Log();

        $query = $model->newQuery()
            ->leftJoin('users', 'users.id', '=', 'logs.user_id');

        $aliases = [
            'user' => 'CONCAT(users.last_name, " ", users.first_name, " ", users.middle_name)',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function show($id)
    {
        $log = Log::findOrFail($id);

        return view('admin.logs.show', [
            'log' => $log
        ]);
    }
}
