<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rafwell\Simplegrid\Grid;

class GroupsController extends Controller
{
    public function index()
    {
       return view('admin.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name','id');
        return view('admin.groups.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name');
        $validator = \Validator::make($data,[
            'name' => 'required|min:4|unique:groups,name',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        $group = Group::create($data);
        $users = explode(',',$request->get('users'));

        $group->users()->sync($users);

        return redirect()->route('admin.groups.index')->with('message', 'Your template has been created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $users = User::pluck('name','id');
        return view('admin.groups.edit', compact('group', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $data = $request->only('name', 'users');
        $validator = \Validator::make($data,[
            'name' => 'required|string|min:3',
            'users' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $group->update($data);
        $users = explode(',', $request->get('users'));
        $group->users()->sync($users);
        return redirect()->route('admin.groups.index')->with(['message' => 'Your template has been updated successfuly']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index');
    }

}
