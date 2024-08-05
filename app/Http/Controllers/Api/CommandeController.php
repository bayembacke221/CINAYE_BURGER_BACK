<?php

namespace App\Http\Controllers\Api;

use App\Http\ApiValidator;
use App\Http\Controllers\Controller;
use App\Notifications\FactureNotification;
use App\Repositories\CommandeRepository;
use App\Http\ApiResponse;
use App\Services\FactureService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommandeController extends Controller
{
    protected $commandeRepository;
    protected $factureService;

    public function __construct(CommandeRepository $commandeRepository, FactureService $factureService)
    {
        $this->commandeRepository = $commandeRepository;
        $this->factureService = $factureService;
    }

    public function index()
    {
        $commandes = $this->commandeRepository->all();
        return ApiResponse::success($commandes);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::commandeRules());
            $commande = $this->commandeRepository->create($validatedData);
            return ApiResponse::success($commande, 'Commande créée avec succès', 201);
        } catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function show($id)
    {
        $commande = $this->commandeRepository->show($id);
        return ApiResponse::success($commande);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::commandeRules());
            $commande = $this->commandeRepository->update($validatedData, $id);
            return ApiResponse::success($commande, 'Commande mise à jour avec succès');
        } catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function destroy($id)
    {
        $this->commandeRepository->delete($id);
        return ApiResponse::success(null, 'Commande supprimée avec succès');
    }

    public function changeEtat(Request $request, $id)
    {
        $etat = $request->input('etat');
        $commande = $this->commandeRepository->changeEtat($id, $etat);

        if ($etat === 'terminé') {
            $pdfContent = $this->factureService->generatePDF($commande);
            $commande->client->notify(new FactureNotification($commande, $pdfContent));
        }

        return ApiResponse::success($commande, 'État de la commande mis à jour avec succès');
    }


    public function annuler($id)
    {
        $commande = $this->commandeRepository->annuler($id);
        return ApiResponse::success($commande, 'Commande annulée avec succès');
    }

    public function statistiquesJour()
    {
        $stats = $this->commandeRepository->getStatistiquesJour();
        return ApiResponse::success($stats, 'Statistiques du jour récupérées avec succès');
    }

    public function getCommandesEnCours()
    {
        $commandes = $this->commandeRepository->getCommandesEnCours();
        return ApiResponse::success($commandes, 'Commandes en cours récupérées avec succès');
    }

    public function getCommandesValidees()
    {
        $commandes = $this->commandeRepository->getCommandesValidees();
        return ApiResponse::success($commandes, 'Commandes validées récupérées avec succès');
    }

    public function getCommandesAnnulees()
    {
        $commandes = $this->commandeRepository->getCommandesAnnulees();
        return ApiResponse::success($commandes, 'Commandes annulées récupérées avec succès');
    }

    public function getCommandesJournalieres($date)
    {
        $commandes = $this->commandeRepository->getCommandesJournalieres($date);
        return ApiResponse::success($commandes, 'Commandes du jour récupérées avec succès');
    }

    public function getRecettesJournalieres($date)
    {
        $recettes = $this->commandeRepository->getRecettesJournalieres($date);
        return ApiResponse::success($recettes, 'Recettes du jour récupérées avec succès');
    }

    public function getCommandesByClient($clientId)
    {
        $commandes = $this->commandeRepository->getCommandesByClient($clientId);
        return ApiResponse::success($commandes, 'Commandes du client récupérées avec succès');
    }
}
