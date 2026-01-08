# Guide d'utilisation du système Rubriques

## 🎯 Vue d'ensemble
Le système de rubriques permet de gérer deux types de contenu :
- **📰 Actualités** : Informations et nouvelles du secteur éducatif
- **💡 Astuces & Conseils** : Conseils pratiques pour les étudiants

## 📊 État actuel (après seeder)
- ✅ **50 rubriques créées** (25 actualités + 25 astuces)
- ⭐ **8 rubriques en vedette** (affichées en priorité)
- 🏷️ **Tags diversifiés** pour la recherche et le filtrage
- 📅 **Dates étalées** sur 6 mois pour un contenu réaliste

## 🔧 Administration
### Accès
- **URL** : `/admin/rubrique`
- **Menu** : Sidebar admin → "Rubriques"

### Fonctionnalités disponibles
1. **Listing** : Vue d'ensemble avec filtres par type et statut
2. **Création** : Formulaire complet avec éditeur CKEditor
3. **Édition** : Modification de toutes les propriétés
4. **Basculement** : Toggle rapide pour publication et vedette
5. **Suppression** : Avec confirmation SweetAlert

### Champs principaux
- **Titre** : Titre de la rubrique
- **Contenu** : Contenu riche (CKEditor 5)  
- **Résumé** : Description courte (meta description)
- **Type** : Actualité ou Astuce & Conseil
- **Tags** : Mots-clés pour la recherche
- **Image** : Image principale (via Spatie Media Library)
- **Statuts** : Publié/Brouillon, En vedette/Normal

## 🌐 Affichage Frontend
### Pages disponibles
- **Actualités** : `/actualites`
- **Astuces & Conseils** : `/astuces-conseils`  
- **Détail** : `/rubrique/{slug}`
- **Recherche** : `/recherche-rubriques`

### Sections homepage
- Bloc actualités récentes (3 dernières)
- Bloc astuces populaires (3 plus vues)
- Sections configurables via l'admin

## 🛠️ Commandes utiles

### Vérification
```bash
php artisan verify:rubriques
```
Affiche les statistiques et exemples de rubriques

### Re-génération (si nécessaire)
```bash
php artisan db:seed --class=RubriqueRefreshSeeder
```
Supprime et recrée 50 rubriques complètes

### Seeder simple (ajout)
```bash  
php artisan db:seed --class=RubriqueSimpleSeeder
```
Ajoute 50 rubriques supplémentaires (attention aux doublons)

## 📸 Gestion des images
- **Upload** : Via l'interface d'administration
- **Formats** : JPG, PNG, WebP recommandés
- **Taille** : 800x600px optimal
- **Conversions** : Thumbnails automatiques via Spatie

## 🎨 Personnalisation
### Ajouter un nouveau type de rubrique
1. Modifier `TYPE_*` dans `Rubrique.php`
2. Ajouter les scopes correspondants
3. Mettre à jour les vues et routes

### Modifier l'affichage
- **Admin** : `resources/views/backend/rubrique/`
- **Frontend** : `resources/views/frontend/actualites/` et `astuces-conseils/`

## 📈 SEO et performance
- **Slugs uniques** : Générés automatiquement
- **Meta descriptions** : Champ résumé utilisé
- **Pagination** : 12 rubriques par page
- **Cache** : Prêt pour mise en cache (Redis recommandé)

## 🔍 Recherche et filtrage
- **Recherche globale** : Dans titre, contenu, tags
- **Filtres admin** : Par type, statut, auteur
- **Tags** : Système de mots-clés flexible

---
**🚀 Système prêt à l'emploi !**
Les 50 rubriques de démonstration permettent de tester toutes les fonctionnalités.