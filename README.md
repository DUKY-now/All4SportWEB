# All4SportWEB

Git pour le site internet d'All4Sport 
AP n°4

## Tâche données
Télio: 

Stéphane: Rédaction du Readme, Gestion du Trello

Théo: 


## Technologies utilisées
- Communication et partage de documents: Discord, GitHub
- Developpement: VSCode et Intellij
- I.A: Chat GPT, Claude, Perplexity
- Conception: Mocodo pour le MCD, Figma pour les visuels de début de projet
- Organisation: Trello 
<link src="https://trello.com/b/0E0QqSeB/all4sportweb">

## création d'un MCD avec MocodoOnline 

le mcd a été réalisé grace au site MOCODO. Dont voici le lien:
https://www.mocodo.net

"SITUE, 0N RAYON, 11 STOCK
STOCK: id, lieu_stockage, quantite_disponible
STOCKE, 0N PRODUIT, 11 STOCK
COMPOSER: quantite, prix_unitaire, sous_total
COMPOSE, 0N COMMANDE, 11 COMPOSER
COMMANDE: id, numero_commande, statut, mode_livraison, date_commande
LIVREE_A, 0N COMMANDE, 1N ADRESSE

RAYON: id, nom_rayon, description
:
PRODUIT: id, nom_produit, description, prix_vente, image_url
CONCERNE_CMD, 0N PRODUIT, 11 COMPOSER
:
PASSE, 0N CLIENT, 11 COMMANDE
ADRESSE: id, rue, code_postal, ville, pays, complement

:
:
CONCERNE_PANIER, 0N PRODUIT, 11 LIGNE_PANIER
LIGNE_PANIER: id, quantite
CONTIENT_PANIER, 0N CLIENT, 11 LIGNE_PANIER
CLIENT: id, role, nom, prenom, email, mot_de_passe, telephone, date_inscription
HABITE, 0N CLIENT, 1N ADRESSE"
<img width="865" height="408" alt="MCD" src="https://github.com/user-attachments/assets/146cd062-1cb8-4b99-8278-adaeb1de773d" />



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

## Tâches réalisées
06/01/2026:
Lecture du cahier des charges
Création du MCD

13/01/2026:
Correction et finalisation du MCD

20/01/2026:
Création du projet symfony 
Création des pages:
- panier: Télio
- Mes infos: Stéphane
- Création de la table Client Théo


27/01:
- bdd 
- design page vente

03/02:
- Correction de bugs qui pouvaient bloquer la suite des tâches

10/02:
- redefinition des tâches et du projet
