<?php

namespace App\Http\Controllers\Api;

use App\Http\ApiValidator;
use App\Http\Controllers\Controller;
use App\Repositories\BurgerRepository;
use App\Http\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BurgerController extends Controller
{
    protected $burgerRepository;

    public function __construct(BurgerRepository $burgerRepository)
    {
        $this->burgerRepository = $burgerRepository;
    }

    public function index()
    {
        $burgers = $this->burgerRepository->all();
        return ApiResponse::success($burgers);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::burgerRules());
            $burger = $this->burgerRepository->create($validatedData);
            return ApiResponse::success($burger, 'Burger créé avec succès', 201);
        } catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function show($id)
    {
        $burger = $this->burgerRepository->show($id);
        return ApiResponse::success($burger);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = ApiValidator::validate($request->all(), ApiValidator::burgerRules());
            $burger = $this->burgerRepository->update($validatedData, $id);
            return ApiResponse::success($burger, 'Burger mis à jour avec succès');
        } catch (ValidationException $e) {
            return ApiResponse::error('Erreur de validation', 422, $e->errors());
        }
    }

    public function destroy($id)
    {
        $this->burgerRepository->delete($id);
        return ApiResponse::success(null, 'Burger supprimé avec succès');
    }

    public function archive($id)
    {
        $burger = $this->burgerRepository->archive($id);
        return ApiResponse::success($burger, 'Burger archivé avec succès');
    }
}
