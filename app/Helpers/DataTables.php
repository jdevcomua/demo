<?php

namespace App\Helpers;

use DB;
use Illuminate\Http\Request;

class DataTables
{
    /**
     * @param Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $aliases
     * @return array
     */
    public static function provide(
        Request $request,
        \Illuminate\Database\Eloquent\Model $model,
        \Illuminate\Database\Eloquent\Builder $query = null,
        array $aliases = null)
    {
        $table = $model->getTable();
        $tableAttr = array_keys(
            array_diff_key(
                array_merge(
                    array_flip(
                        array_filter($model->getFillable(),
                            function ($v) { return (strpos($v, '_') !== 0); }
                        )
                    ),
                    array_flip($model->getDates())
                ),
                array_flip($model->getHidden())
            )
        );

        if ($request->has(['draw', 'start', 'length'])) {
            $req = $request->all();

            if ($tableAttr) {
                foreach ($tableAttr as $attr) {
                    $selects[] = DB::raw($table . '.' . $attr);
                }
            }
            if ($aliases) {
                foreach ($aliases as $alias => $value) {
                    $selects[] = DB::raw($value . ' AS ' . $alias);
                }
            }
            if(!$query) $query = $model->newQuery();
            $query->select($selects);

            if ($request->has('columns')) {
                $columns = $req['columns'];
                if (is_array($columns)) {
                    foreach ($columns as $column) {
                        try {
                            if ($column['search']['value'] !== null) {
                                if (((array_search($column['data'], $model->getDates()) !== false
                                    && preg_match("/^[a-z-:.\d]{1}[a-z-:.\d\s]*[a-z-:.\d]{1}$/i", $column['search']['value'])))
                                || array_search($column['data'], $model->getDates()) === false) {
                                    //reverting format
                                    $searchValue = explode('.', $column['search']['value']);
                                    $searchValue = array_reverse($searchValue);
                                    if (count($searchValue) === 2) {
                                        if (strlen($searchValue[0]) === 1 || strlen($searchValue[0]) === 3) {
                                            $searchValue[0] .= '%';
                                        }
                                    } elseif (count($searchValue) === 3) {
                                        $searchValue[0] .= '%';
                                    }
                                    $searchValue = implode('-', $searchValue);
                                    if ($column['data'] ==='object_id' && (strpos($column['search']['value'], '#')) !== false) {
                                        $searchValue = str_replace('#', '', $searchValue);
                                    }

                                    if (!$aliases || !array_key_exists($column['data'], $aliases)) {
                                        $query->where($table . '.' . $column['data'], 'like',
                                            '%' . $searchValue . '%');
                                    } else {
                                        if (!self::isHavingSearch($aliases[$column['data']])) {
                                            $query->whereRaw($aliases[$column['data']] . ' like '
                                                . '\'%' . $column['search']['value'] . '%\''
                                            );
                                        } else {
                                            $query->havingRaw($aliases[$column['data']] . ' like '
                                                . '\'%' . $column['search']['value'] . '%\''
                                            );
                                        }
                                    }

                                } else {
                                    $kyrillSymolsInDates = true;
                                }
                            }
                        } catch (\Exception $exception) {}
                    }
                }

                if ($request->has('order')) {
                    try {
                        if (!$aliases || !array_key_exists($columns[$req['order'][0]['column']]['data'], $aliases)) {
                            $query->orderBy(
                                $table. '.' .$columns[$req['order'][0]['column']]['data'],
                                $req['order'][0]['dir']
                            );
                        } else {
                            $query->orderByRaw(
                                $aliases[$columns[$req['order'][0]['column']]['data']] . ' ' .
                                $req['order'][0]['dir']
                            );
                        }
                    } catch (\Exception $exception) {}
                }
            }

            $response['draw'] = +$req['draw'];

            $response["recordsTotal"] = $model->count();
            if ($query->getQuery()->wheres) {
                if ($query->getQuery()->groups || $query->getQuery()->havings) {
                    $q = $model->newQuery();
                    $response["recordsFiltered"] = $q
                        ->selectRaw('count(*) as count')
                        ->fromRaw('('.$query->toSql() . ') as a', $query->getBindings())
                        ->first()->count;
                } else {
                    $response["recordsFiltered"] = $query->count();
                }
            } else {
                $response["recordsFiltered"] = $response["recordsTotal"];
            }

            if (!isset($kyrillSymolsInDates)) {
                $response['data'] = $query->offset($req['start'])
                    ->limit($req['length'])
                    ->get()
                    ->toArray();
            } else {
                $response['data'] = [];
                $response["recordsFiltered"] = $response["recordsTotal"] = 0;
            }
            return $response;
        }
        return null;
    }

    private static function isHavingSearch($alias)
    {
        $mustUseHaving = ['GROUP_CONCAT', 'COUNT'];
        foreach ($mustUseHaving as $m) {
            if (strpos($alias, $m) !== false) return true;
        }
        return false;
    }
}