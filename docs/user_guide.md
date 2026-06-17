# Guide de l'Utilisateur (Manuel d'Utilisation)

Ce guide détaille le fonctionnement de l'application côté visiteur public, espace client et espace administrateur.

---

## 1. Espace Visiteur Public (Non connecté)

### 1.1 Page d'accueil
La page d'accueil affiche les terrains de sports disponibles à la location.
- **Rechercher** : Utilisez la barre de recherche textuelle pour chercher par nom.
- **Filtrer** : Sélectionnez une ville spécifique ou insérez un budget maximum par heure. Cliquez sur "Filtrer" pour actualiser instantanément la liste.

### 1.2 Inscription et Connexion
- Pour réserver, cliquez sur **Connexion** en haut à droite, saisissez vos identifiants ou cliquez sur **S'inscrire** pour créer un nouveau compte client en remplissant le formulaire.

---

## 2. Espace Client (Connecté)

### 2.1 Espace Dashboard
Une fois connecté, le client accède à son tableau de bord affichant :
- Des indicateurs sur le volume global de ses demandes (total, en attente, acceptées, terminées).
- La liste de ses 5 dernières réservations.

### 2.2 Explorer et Louer un Terrain
1. Cliquez sur **Terrains** dans le menu latéral.
2. Cliquez sur **Voir les détails / Réserver** sur le terrain de votre choix.
3. Sur la fiche de présentation, saisissez la **Date de début** et la **Date de fin** souhaitées.
4. L'application calcule automatiquement le montant total en temps réel sous le calendrier.
5. Cliquez sur **Soumettre la réservation**.

### 2.3 Suivi et Annulation
- Accédez à **Mes Réservations**. Vous visualisez le tableau complet de vos locations avec les statuts de validation et de paiement.
- Si une réservation est en attente (`En attente`), un bouton **Annuler** est disponible pour annuler immédiatement la demande.

### 2.4 Mon Profil
- Accédez à **Mon Profil** pour modifier vos données de contact (nom, prénom, email, téléphone).
- Vous pouvez également modifier votre mot de passe en saisissant votre mot de passe actuel.

---

## 3. Espace Administrateur

### 3.1 Tableau de bord statistique
L'administrateur dispose de indicateurs clés mis à jour en temps réel :
- **Chiffre d'Affaires total** généré.
- Graphiques dynamiques Chart.js : Ventes par mois et Popularité relative de chaque terrain de sport.

### 3.2 Gérer les Terrains (CRUD)
- **Ajouter un terrain** : Cliquez sur **Ajouter un terrain** (en haut à droite), remplissez les détails et sélectionnez le fichier d'image. Validez.
- **Modifier** : Cliquez sur **Modifier** sur le terrain voulu dans le tableau, ajustez les informations ou l'image et enregistrez.
- **Supprimer** : Cliquez sur l'icône de corbeille rouge pour retirer un terrain. Une invite de confirmation prévient des suppressions accidentelles.

### 3.3 Traiter les Demandes de Réservation
1. Allez sur **Réservations**.
2. Les demandes en attente affichent deux boutons : **Accepter** et **Refuser**.
   - Accepter bloque le terrain pour ces dates.
   - Refuser libère le créneau.

### 3.4 Suivi des Paiements
- Allez sur **Paiements**.
- Pour chaque réservation acceptée en attente de paiement, l'administrateur peut sélectionner la ligne dans le panneau de droite **Enregistrer un Paiement**. Le montant total de la facture est pré-rempli.
- Sélectionnez le mode de règlement (Espèces, Carte, Virement) et enregistrez la transaction. Cela clôture automatiquement la réservation (`Terminée`) et libère à nouveau le statut du terrain pour les locations futures.
