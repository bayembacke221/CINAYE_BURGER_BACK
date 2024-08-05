<?php

namespace App\Services;

use App\Models\Commande;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureService
{
    public function generatePDF(Commande $commande)
    {
        $pdf = PDF::loadView('factures.template', ['commande' => $commande]);
        return $pdf->output();
    }
}
