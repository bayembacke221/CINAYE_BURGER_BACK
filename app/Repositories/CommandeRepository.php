<?php

namespace App\Repositories;

use App\Models\Commande;
use Illuminate\Support\Facades\DB;

class CommandeRepository extends BaseRepository
{
    public function __construct(Commande $model)
    {
        parent::__construct($model);
    }

    public function changeEtat($id, $etat)
    {
        $commande = $this->model->findOrFail($id);
        $commande->etat = $etat;
        $commande->save();
        return $commande;
    }

    public function  getStatistiquesJour(){
        return $this->model->select(DB::raw('count(*) as nombre, sum(paiements.montant) as recette, date(commandes.created_at) as date'))
            ->join('paiements', 'commandes.id', '=', 'paiements.commande_id')
            ->where('etat', 'paye')
            ->groupBy('date')
            ->get();
    }

    public function getCommandesEnCours()
    {
        return $this->model->where('etat', 'en_cours')->get();
    }

    public function getCommandesValidees()
    {
        return $this->model->where('etat', 'termine')->get();
    }

    public function getCommandesAnnulees()
    {
        return $this->model->where('etat', 'annule')->get();
    }

    public function getCommandesJournalieres($date)
    {
        return $this->model->whereDate('created_at', $date)->get();
    }

    public function getRecettesJournalieres($date)
    {
        return $this->model->whereDate('paiements.created_at', $date)
            ->where('etat', 'paye')
            ->join('paiements', 'commandes.id', '=', 'paiements.commande_id')
            ->sum('paiements.montant');
    }

    public function getCommandesByClient($clientId)
    {
        return $this->model->where('client_id', $clientId)->get();
    }

    public function annuler($id)
    {
        $commande = $this->model->findOrFail($id);
        $commande->etat = 'annule';
        $commande->save();
        return $commande;
    }
}
