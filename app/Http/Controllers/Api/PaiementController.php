<?php

namespace App\Http\Controllers\Api;

use App\Http\ApiValidator;
use App\Http\Controllers\Controller;
use App\Repositories\PaiementRepository;
use App\Http\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaiementController extends Controller
{
    protected $paiementRepository;

    public function __construct(PaiementRepository $paiementRepository)
    {
        $this->paiementRepository = $paiementRepository;
    }

    public function index()
    {
        $paiements = $this->paiementRepository->all();
        return ApiResponse::success($paiements);
    }


    public function show($id)
    {
        $paiement = $this->paiementRepository->show($id);
        return ApiResponse::success($paiement);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::paiementRules());
            $paiement = $this->paiementRepository->update($validatedData, $id);
            return ApiResponse::success($paiement, 'Paiement mis à jour avec succès');
        }catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function destroy($id)
    {
        $this->paiementRepository->delete($id);
        return ApiResponse::success(null, 'Paiement supprimé avec succès');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::paiementRules());
            $paiement = $this->paiementRepository->createPaiement($validatedData['commande_id'], $validatedData['montant']);
            return ApiResponse::success($paiement, 'Paiement créé avec succès', 201);
        }catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function getPaiementsByDate($date)
    {
        $paiements = $this->paiementRepository->getPaiementsByDate($date);
        return ApiResponse::success($paiements);
    }

    public function getTotalPaiementsByDate($date)
    {
        $total = $this->paiementRepository->getTotalPaiementsByDate($date);
        return ApiResponse::success($total);
    }

    public function getPaiementByCommandeId($id)
    {
        $paiement = $this->paiementRepository->getPaiementByCommandeId($id);
        return ApiResponse::success($paiement);
    }

}
