<?php

namespace App\Repositories;

use App\Models\Burger;

class BurgerRepository extends BaseRepository
{
    public function __construct(Burger $model)
    {
        parent::__construct($model);
    }

    public function archive($id)
    {
        $burger = $this->model->findOrFail($id);
        $burger->archive = true;
        $burger->save();
        return $burger;
    }
}
