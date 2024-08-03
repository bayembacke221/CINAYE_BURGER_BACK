<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository extends BaseRepository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

    public function findByPhone($phone)
    {
        return $this->model->where('telephone', $phone)->first();
    }

    public function getClientCommandes($clientId)
    {
        return $this->model->findOrFail($clientId)->commandes;
    }


}
