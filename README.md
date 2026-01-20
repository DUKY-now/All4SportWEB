# All4SportWEB

Git pour le site internet d'All4Sport 
AP n°4

## Tâche données
Télio: 

Stéphane: Rédaction du Readme

Théo: 


## création d'un MCD avec MocodoOnline 

le mcd a été réalisé grace au site MOCODO. Dont voici le lien:
https://www.mocodo.net


:
:

ADRESSE: id_adresse, rue, code_postal, ville, pays, complement
LIVRER_A, 0N COMMANDE, 1N ADRESSE
COMMANDE: id_commande, numero_commande, statut, mode_livraison
COMPOSER, 0N COMMANDE, 0N PRODUIT: quantite, prix_unitaire, sous_total
PRODUIT: id_produit, nom_produit, description, prix_vente, image_url
ETRE_STOCKE, 1N PRODUIT, 0N STOCK

:
CLIENT: id_client, role,nom, prenom, email, mot_de_passe, telephone, date_inscription
PASSER, 1N CLIENT, 0N COMMANDE: 
RAYON: id_rayon, nom_rayon, description
POSSEDER, 11 RAYON, 1N PRODUIT
STOCK: id_stock, lieu_stockage, quantite_disponible
<img width="1170" height="722" alt="image" src="https://github.com/user-attachments/assets/0e0a7b0c-c927-4901-917b-ad59cb8f3591" />


Bootstrap est un site qui peut nous aider a donner du style a nos pages web. Il peut gerer le responsive et peut etre utilisé pour notre formulaire.
La documentation est du code donc il sera facile de l'implementer a notre site;
https://getbootstrap.com

Pour pouvoir utilisé Bootstrap, il faut un a mettre dans le "head", ils sont disponible directement sur le site.


## Commande utilisées:
- Symfony new /chemin du projet/ --Webapp
cette commande est utile pour creer le projet.

- symfony serve
pour lancer le serveur web.

- php bin/console make:controller
Création du controller Home (Page d'accueil), et utilisée pour chaque page.


## Page du site
Page a créer en priorité:
- Home ( Accueil)
- GestionCommandsCollaborateur (Gérer l'état des commandes)
- MesInfos ( Informations du client)
- Produits( Afficahge des produits avec les categorie) et tout ce qui en découle
- Stock (Gestion du stock) ( Fonctionalité d'achat de restock via prit du marché )

Page annexes:
- Trouvez un Magasin ( Recherche de magasin )
- Suivis de Livraison( Pour client / personnels (  affichage différents) )
- Avis ( Gestion des avis )
- Panier ( Gestion du panier )
- Meilleurs ventes ( Affichage des meilleurs ventes )
- Promo / carte cadeaux ( Promotion et carte cadeaux )
