<?php

namespace App\Http\Controllers\Admin;

use App\Events\AnimalRequestAccepted;
use App\Events\AnimalRequestDeclined;
use App\Filters\UserFilter;
use App\Filters\UsersFilter;
use App\Helpers\DataTables;
use App\Models\Animal;
use App\Models\AnimalsRequest;
use App\Services\Printable\DataProviders\FilteredUsers;
use App\Services\Printable\ExcelPrintService;
use App\User;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdministratingController extends Controller
{
    public function users()
    {
        return view('admin.administrating.users');
    }

    public function userData(Request $request)
    {
        $model = new User();

        $query = $model->newQuery()
            ->where('banned' , '=', false)
            ->leftJoin('user_emails', 'user_emails.user_id', '=', 'users.id')
            ->leftJoin('user_phones', 'user_phones.user_id', '=', 'users.id')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->groupBy('users.id');

        $aliases = [
            'emails' => 'GROUP_CONCAT(DISTINCT `user_emails`.email SEPARATOR \'|\')',
            'phones' => 'GROUP_CONCAT(DISTINCT `user_phones`.phone SEPARATOR \'|\')',
            'role_names' =>'GROUP_CONCAT(DISTINCT `roles`.display_name SEPARATOR \'|\')',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = true;
        $user->save();

        return redirect()
            ->back()
            ->with('success_user', 'Користувач успішно заблокований!');
    }

    public function bannedUsers()
    {
        return view('admin.administrating.banned');
    }

    public function bannedUsersData(Request $request)
    {
        $model = new User();

        $query = $model->newQuery()
            ->where('banned' , '=', true)
            ->leftJoin('user_emails', 'user_emails.user_id', '=', 'users.id')
            ->leftJoin('user_phones', 'user_phones.user_id', '=', 'users.id')
            ->groupBy('users.id');

        $aliases = [
            'emails' => 'GROUP_CONCAT(DISTINCT `user_emails`.email SEPARATOR \'|\')',
            'phones' => 'GROUP_CONCAT(DISTINCT `user_phones`.phone SEPARATOR \'|\')',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = false;
        $user->save();

        return redirect()
            ->back()
            ->with('success_user', 'Користувач успішно розблокований!');
    }

    public function object($type, $id)
    {
        switch ($type) {
            case 'Користувач' : return redirect()->route('admin.db.users.show', $id);
            case 'Тварина' : return redirect()->route('admin.db.animals.edit', $id);
            case 'Організація' : return redirect()->route('admin.info.directories.edit.organization', $id);
            default: abort(404);
        }
    }

    public function animalsRequests()
    {
        return view('admin.administrating.requests');
    }

    public function animalsRequestsData(Request $request)
    {
        $model = new AnimalsRequest();

        $query = $model->newQuery()
            ->leftJoin('users', 'animals_requests.user_id', '=', 'users.id')
            ->leftJoin('animals', 'animals_requests.animal_id', '=', 'animals.id')
            ->leftJoin('breeds', 'animals_requests.breed_id', '=', 'breeds.id')
            ->leftJoin('species', 'animals_requests.species_id', '=', 'species.id')
            ->leftJoin('furs', 'animals_requests.fur_id', '=', 'furs.id')
            ->leftJoin('colors', 'animals_requests.color_id', '=', 'colors.id')
            ->groupBy('animals_requests.id');

        $aliases = [
            'user' => 'CONCAT(`users`.last_name, \' \', `users`.first_name)',
            'species_name' => 'species.name',
            'breed_name' => 'breeds.name',
            'fur_name' => 'furs.name',
            'color_name' => 'colors.name',
            'type' => '(`animals_requests`.`animal_id` IS NOT NULL)',
            'address' => 'CONCAT(
                 COALESCE(`animals_requests`.street, " "), ", " ,
                 COALESCE(`animals_requests`.building, " "), ", " ,
                 COALESCE(`animals_requests`.apartment, " ")
           )',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function confirmAnimalsRequest($id)
    {
        $animalRequest = AnimalsRequest::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        $animal = Animal::findOrFail($animalRequest->animal_id);
        $animal->user_id = $animalRequest->user_id;
        $animal->save();

        $user = User::find($animalRequest->user_id);

        event(new AnimalRequestAccepted($user, [$animal]));

        return redirect()
            ->back()
            ->with('success_request', 'Запит було прийнято успішно');
    }

    public function proceedAnimalsRequest($id)
    {
        $animalRequest = AnimalsRequest::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        return redirect()
            ->back()
            ->with('success_request', 'Запит було оброблено успішно');
    }

    public function cancelAnimalsRequest($id)
    {
        $animalRequest = AnimalsRequest::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        $user = User::find($animalRequest->user_id);

        event(new AnimalRequestDeclined($user, [$animalRequest->animal]));

        return redirect()
            ->back()
            ->with('success_request', 'Запит було відхилено успішно!');
    }

    public function usersFilter(Request $request, UsersFilter $filter)
    {
        $users = User::all();

        if ($request->method() === 'POST') {
            $users = $filter->process($request)->get();
            $request->flash();
        }

        return view('admin.filterable.users', compact('users'));
    }

    public function usersFilterDownload(Request $request, UsersFilter $filter)
    {
        $users = $filter->process($request)->get();

        $dataProvider = new FilteredUsers($users);

        $printService = new ExcelPrintService();
        $printService->init($dataProvider, 'print.tables_with_sign_place_pdf', 'Користувачі');
        $printService->download();
    }
}
