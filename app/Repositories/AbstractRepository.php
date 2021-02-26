<?php

namespace App\Repositories;

class  AbstractRepository
{
    protected $model;

    public function __construct()
    {
        $this->model =  $this->resolveModel();
    }

    public function all ()
    {
        return $this->model->orderBy('name','asc')->get();
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    protected function resolveModel()
    {
        return app($this->model);
    }
}
