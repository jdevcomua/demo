<?php

namespace App\Helpers;

use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Orderable {

    public function normalizeOrder()
    {
        /** @var Model $this */
        $items = $this->all()->sortBy('order');

        /** @var Collection $items */
        DB::transaction(function () use ($items) {
            $counter = 1;

            foreach ($items as $item) {
                $item->order = $counter++;
                $item->save();
            }
        });
    }

    public function moveOrderUp()
    {
        /** @var Model $this */
        $query = $this->newQuery();

        $prev = $query->where('order', '<', $this->order)
            ->orderByDesc('order')
            ->first();

        if ($prev) $this->swapOrders($this, $prev);
    }

    public function moveOrderDown()
    {
        /** @var Model $this */
        $query = $this->newQuery();

        $next = $query->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();

        if ($next) $this->swapOrders($this, $next);
    }

    /**
     * @param Model $model1
     * @param Model $model2
     */
    private function swapOrders($model1, $model2)
    {
        $buff = $model1->order;
        $model1->order = $model2->order;
        $model2->order = $buff;

        $model1->save();
        $model2->save();
    }

}