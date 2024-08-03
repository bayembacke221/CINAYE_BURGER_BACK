<?php

namespace App\Http;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiValidator
{
    /**
     * Valider les données en fonction des règles spécifiées.
     *
     * @param array $data Les données à valider
     * @param array $rules Les règles de validation
     * @param array $messages Messages d'erreur personnalisés (optionnel)
     * @throws ValidationException
     * @return array Les données validées
     */
    public static function validate(array $data, array $rules, array $messages = [])
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Règles de validation pour la création d'un burger.
     *
     * @return array
     */
    public static function burgerRules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'image' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    /**
     * Règles de validation pour la création d'un client.
     *
     * @return array
     */
    public static function clientRules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ];
    }

    /**
     * Règles de validation pour la création d'une commande.
     *
     * @return array
     */
    public static function commandeRules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'burger_id' => 'required|exists:burgers,id',
            'quantite' => 'required|integer|min:1',
        ];
    }

    /**
     * Règles de validation pour la création d'un paiement.
     *
     * @return array
     */
    public static function paiementRules()
    {
        return [
            'commande_id' => 'required|exists:commandes,id|unique:paiements,commande_id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
        ];
    }
}
