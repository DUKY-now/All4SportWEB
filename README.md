# All4SportWEB

# All4Sport_Web
Git pour le site internet d'All4Sport


création d'un MCD avec MocodoOnline 

https://www.mocodo.net
:
:
VALIDER, 0N COLLABORATEUR, 0N COMMANDE: date_validation, commentaire
COLLABORATEUR: id_collaborateur, nom, prenom, email, mot_de_passe, role
:
:

ADRESSE: id_adresse, rue, code_postal, ville, pays, complement
LIVRER_A, 0N COMMANDE, 1N ADRESSE
COMMANDE: id_commande, numero_commande, statut, mode_livraison
COMPOSER, 0N COMMANDE, 0N PRODUIT: quantite, prix_unitaire, sous_total
PRODUIT: id_produit, nom_produit, description, prix_vente, image_url
ETRE_STOCKE, 1N PRODUIT, 0N STOCK

:
CLIENT: id_client, nom, prenom, email, mot_de_passe, telephone, date_inscription
PASSER, 1N CLIENT, 0N COMMANDE: 
RAYON: ²id_rayon, nom_rayon, description
POSSEDER, 11 RAYON, 1N PRODUIT
STOCK: id_stock, lieu_stockage, quantite_disponible
<img width="1170" height="722" alt="image" src="https://github.com/user-attachments/assets/0e0a7b0c-c927-4901-917b-ad59cb8f3591" />


Bootstrap, permet de creer des pages facilement (peut etre utilisé pour une page de paiement), il gère le responsive
utile pour des formulaires (la doc est du code donc facile a implémenter)
https://getbootstrap.com

les liens cdn peuvent etre trouvés avec bootstrap et google
ce sont des libraires en ligne donc il faut internet
pour les trouvers ils sont dans l'accueil et se mettent dans le fichier base


# Commande utilisées:
- Symfony new /chemin du projet/ --Webapp
cette commande est utile pour creer le projet.

- symfony serve
pour lancer le serveur web.