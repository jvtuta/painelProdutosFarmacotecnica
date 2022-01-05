<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

abstract class Repository {

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}