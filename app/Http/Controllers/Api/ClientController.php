<?php

namespace App\Http\Controllers\Api;

use App\Http\ApiValidator;
use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;
use App\Http\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index()
    {
        $clients = $this->clientRepository->all();
        return ApiResponse::success($clients);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::clientRules());
            $client = $this->clientRepository->create($validatedData);
            return ApiResponse::success($client, 'Client créé avec succès', 201);
        } catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function show($id)
    {
        $client = $this->clientRepository->show($id);
        return ApiResponse::success($client);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::clientRules());
            $client = $this->clientRepository->update($validatedData, $id);
            return ApiResponse::success($client, 'Client mis à jour avec succès');
        } catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function destroy($id)
    {
        $this->clientRepository->delete($id);
        return ApiResponse::success(null, 'Client supprimé avec succès');
    }


    public function getClientCommandes($clientId)
    {
        $commandes = $this->clientRepository->getClientCommandes($clientId);
        return ApiResponse::success($commandes, 'Commandes du client récupérées avec succès');
    }
}
