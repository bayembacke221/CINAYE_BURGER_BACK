<?php

namespace App\Repositories;

use App\Models\Paiement;

class PaiementRepository extends BaseRepository
{
    public function __construct(Paiement $model)
    {
        parent::__construct($model);
    }

    public function createPaiement($commandeId, $montant)
    {
        return $this->model->create([
            'commande_id' => $commandeId,
            'montant' => $montant,
            'date_paiement' => now()
        ]);
    }

    public function getPaiementsByDate($date)
    {
        return $this->model->whereDate('date_paiement', $date)->get();
    }

    public function getTotalPaiementsByDate($date)
    {
        return $this->model->whereDate('date_paiement', $date)->sum('montant');
    }

    public function getPaiementByCommandeId($id){
        return $this->model->where('commande_id', $id)->first();
    }
}
