<!DOCTYPE html>
<html>
<head>
    <title>Facture</title>
</head>
<body>
<h1>Facture pour la commande #{{ $commande->id }}</h1>
<p>Client : {{ $commande->client->nom }} {{ $commande->client->prenom }}</p>
<p>Burger : {{ $commande->burger->nom }}</p>
<p>Quantité : {{ $commande->quantite }}</p>
<p>Total : {{ $commande->burger->prix * $commande->quantite }} €</p>
</body>
</html>
