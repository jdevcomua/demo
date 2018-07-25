<?php

namespace App\Http\Controllers\Admin;

use App\Rules\BirthdayRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function getUsersData()
    {
        $userData = User::leftJoin('animals', 'animals.user_id', '=', 'users.id')
            ->select([
                'users.*',
                \DB::raw('COALESCE(count(animals.id),0) as animals_count')
            ])
            ->groupBy('users.id');

        return Datatables::of($userData)
            ->addColumn('action', function ($animal) {
                return '<a href="' . route('admin.animals.show', $animal->id) . '" class="btn accept btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Редагувати</a>';
            })
            ->make(true);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }
        $user->load('animals');

        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'first_name',
            'last_name',
            'middle_name',
            'email',
            'phone',
            'birthday',
            'gender',
            'passport',
            'residence_address'
        ]);

        $validator = \Validator::make($data,[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'birthday' => [
                'nullable',
                'date',
                'max:255',
                new BirthdayRule
            ],
            'gender' => 'nullable|integer|min:0|max:1',
            'passport' => 'nullable|string|max:255',
            'residence_address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $data['birthday'] = Carbon::createFromFormat('d-m-Y',$data['birthday'])->format("Y-m-d H:i:s");

        $user = User::find($id);
        $user->update($data);
        $user->save();
        return redirect()->back();
    }
}
