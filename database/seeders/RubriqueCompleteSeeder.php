<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rubrique;
use App\Models\User;
use Illuminate\Support\Str;

class RubriqueCompleteSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::first();
        
        // Données pour les actualités
        $actualites = [
            [
                'titre' => 'Réforme du système éducatif ivoirien : ce qui change en 2026',
                'contenu' => 'Le ministère de l\'Éducation nationale annonce une série de réformes majeures qui transformeront le paysage éducatif ivoirien dès la rentrée 2026. Ces changements touchent tous les niveaux d\'enseignement, du primaire au supérieur.

**Les principales innovations :**

1. **Digitalisation des apprentissages** : Introduction obligatoire des tablettes numériques dans toutes les écoles primaires publiques
2. **Nouveau curriculum** : Renforcement des matières scientifiques et technologiques
3. **Évaluation continue** : Fin du système de redoublement automatique
4. **Formation des enseignants** : Programme de formation continue obligatoire de 40 heures par an
5. **Partenariats internationaux** : Accords avec des universités européennes pour les échanges

Cette réforme s\'inscrit dans la vision "Côte d\'Ivoire 2030" et vise à positionner le pays comme un leader régional en matière d\'éducation. Le budget alloué s\'élève à 2,5 milliards de FCFA sur 5 ans.',
                'resume' => 'Découvrez les grandes lignes de la réforme éducative 2026 qui va transformer l\'enseignement en Côte d\'Ivoire.',
                'tags' => ['réforme', 'éducation', 'numérique', 'innovation', 'côte d\'ivoire'],
                'est_featured' => true,
                'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop'
            ],
            [
                'titre' => 'Lancement du programme "École Numérique 2026" en Côte d\'Ivoire',
                'contenu' => 'Le gouvernement ivoirien, en partenariat avec l\'UNESCO et l\'Union Européenne, lance officiellement le programme "École Numérique 2026". Cette initiative ambitieuse vise à équiper 5000 établissements scolaires en matériel informatique d\'ici 2028.

**Objectifs du programme :**

- Réduire la fracture numérique en milieu scolaire
- Former 50 000 enseignants aux outils numériques
- Créer 200 centres de ressources numériques
- Développer des contenus pédagogiques locaux

**Phase pilote :**
100 écoles d\'Abidjan ont été sélectionnées pour tester le programme dès janvier 2026. Chaque établissement recevra :
- 30 tablettes éducatives
- Un tableau numérique interactif
- Une connexion internet haut débit
- Formation du personnel enseignant

Les premiers retours sont très positifs, avec une amélioration de 35% des résultats en mathématiques et sciences.',
                'resume' => 'Le programme École Numérique 2026 va révolutionner l\'apprentissage dans 5000 établissements ivoiriens.',
                'tags' => ['numérique', 'technologie', 'éducation', 'unesco', 'innovation'],
                'est_featured' => true,
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop'
            ],
            [
                'titre' => 'Nouveau partenariat entre universités ivoiriennes et françaises',
                'contenu' => 'L\'Université Félix Houphouët-Boigny d\'Abidjan signe un accord historique avec 5 universités françaises prestigieuses pour développer la mobilité étudiante et la recherche collaborative.

**Universités partenaires :**
- Sorbonne Université (Paris)
- Université de Lyon
- Université de Montpellier
- Sciences Po Bordeaux
- INSA Toulouse

**Programmes concernés :**
- Double diplôme en ingénierie
- Masters conjoints en sciences sociales
- Doctorats en cotutelle
- Échanges Erasmus+

Ce partenariat permettra à 500 étudiants ivoiriens par an de bénéficier de bourses d\'excellence pour poursuivre leurs études en France, tandis que 200 étudiants français viendront étudier en Côte d\'Ivoire.

Le programme inclut également la création d\'un campus délocalisé français à Abidjan, prévu pour 2027, qui accueillera 2000 étudiants.',
                'resume' => 'Accord historique entre universités ivoiriennes et françaises pour la mobilité étudiante et la recherche.',
                'tags' => ['université', 'partenariat', 'international', 'mobilité', 'recherche'],
                'est_featured' => false,
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&h=600&fit=crop'
            ],
        ];

        // Données pour les astuces et conseils
        $astucesConseils = [
            [
                'titre' => '15 techniques infaillibles pour améliorer sa concentration en cours',
                'contenu' => 'La concentration est la clé de la réussite scolaire. Voici 15 techniques éprouvées scientifiquement pour maximiser votre attention pendant les cours.

**Techniques de préparation :**

1. **Hydratation optimale** : Buvez 2 verres d\'eau 30 minutes avant le cours
2. **Petit-déjeuner équilibré** : Privilégiez les glucides complexes et les protéines
3. **Sommeil de qualité** : 7-9 heures de sommeil la nuit précédente
4. **Exercice physique** : 10 minutes de marche rapide avant le cours
5. **Méditation** : 5 minutes de respiration profonde

**Pendant le cours :**

6. **Position assise optimale** : Dos droit, pieds au sol
7. **Prise de notes active** : Méthode Cornell ou mind mapping
8. **Questions régulières** : Posez 3 questions par heure de cours
9. **Participation active** : Levez la main au moins 2 fois
10. **Éviter les distracteurs** : Téléphone en mode avion

**Techniques avancées :**

11. **Technique Pomodoro** : 25 min de concentration, 5 min de pause
12. **Ancrage mental** : Associez un geste à l\'état de concentration
13. **Visualisation** : Imaginez-vous réussir avant le cours
14. **Musique binaural** : Écoutez des fréquences alpha (8-12 Hz)
15. **Alimentation cérébrale** : Noix, myrtilles, chocolat noir

Application de ces techniques pendant 21 jours consécutifs garantit une amélioration notable de votre concentration.',
                'resume' => '15 méthodes scientifiquement prouvées pour booster votre concentration et réussir vos cours.',
                'tags' => ['concentration', 'études', 'techniques', 'réussite', 'méthodologie'],
                'est_featured' => true,
                'image_url' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=600&fit=crop'
            ],
            [
                'titre' => 'Guide complet pour créer un planning de révisions efficace',
                'contenu' => 'Créer un planning de révisions efficace est un art qui demande méthode et organisation. Voici le guide complet pour optimiser votre temps de révision.

**Étape 1 : Analyse et inventaire**

Avant de planifier, faites le point :
- Listez toutes les matières à réviser
- Évaluez votre niveau dans chaque matière (1-5)
- Comptez le nombre de chapitres/leçons
- Identifiez vos créneaux libres dans la semaine

**Étape 2 : Priorisation intelligente**

Utilisez la matrice d\'Eisenhower :
- **Urgent + Important** : Matières à faible niveau avec examen proche
- **Important + Non urgent** : Matières principales du concours
- **Urgent + Non important** : Révisions de dernière minute
- **Ni urgent ni important** : Matières secondaires

**Étape 3 : Planification détaillée**

**Principe des 3-2-1 :**
- 3 heures pour les matières difficiles
- 2 heures pour les matières moyennes  
- 1 heure pour les matières faciles

**Répartition hebdomadaire type :**
- Lundi : Mathématiques (3h) + Anglais (1h)
- Mardi : Français (2h) + Histoire (2h)
- Mercredi : Sciences (3h) + Repos (1h)
- Jeudi : Mathématiques (2h) + Géographie (2h)
- Vendredi : Révisions générales (2h) + Tests (2h)
- Samedi : Matières faibles (4h)
- Dimanche : Repos + Révision légère (2h)

**Outils recommandés :**
- Google Calendar pour la planification
- Forest app pour rester concentré
- Notion pour les notes
- Anki pour la mémorisation

**Conseils pro :**
- Planifiez 80% de votre temps, gardez 20% pour l\'imprévu
- Révisez les mêmes matières aux mêmes heures
- Alternez révision et exercices pratiques
- Prévoyez 1 jour de repos complet par semaine',
                'resume' => 'Méthode complète et détaillée pour créer un planning de révisions personnalisé et efficace.',
                'tags' => ['planning', 'révisions', 'organisation', 'méthodologie', 'gestion temps'],
                'est_featured' => true,
                'image_url' => 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=600&fit=crop'
            ],
            [
                'titre' => 'Comment vaincre le stress des examens : méthodes psychologiques éprouvées',
                'contenu' => 'Le stress des examens peut paralyser même les meilleurs élèves. Voici des méthodes psychologiques scientifiquement validées pour transformer votre anxiété en force.

**Comprendre le stress des examens**

Le stress d\'examen active 3 systèmes :
1. **Système nerveux sympathique** : Accélération cardiaque
2. **Système endocrinien** : Production de cortisol
3. **Système cognitif** : Pensées négatives en boucle

**Méthodes de gestion immédiate**

**Technique 4-7-8 (Dr. Andrew Weil) :**
- Inspirez par le nez pendant 4 secondes
- Retenez votre souffle pendant 7 secondes  
- Expirez par la bouche pendant 8 secondes
- Répétez 4 fois

**Ancrage 5-4-3-2-1 :**
Identifiez autour de vous :
- 5 choses que vous voyez
- 4 choses que vous touchez
- 3 choses que vous entendez
- 2 choses que vous sentez
- 1 chose que vous goûtez

**Méthodes de préparation long terme**

**1. Désensibilisation systématique :**
- Semaine 1 : Visualisez l\'examen en étant détendu
- Semaine 2 : Simulez les conditions d\'examen à la maison
- Semaine 3 : Passez des examens blancs dans l\'établissement
- Semaine 4 : Examens blancs chronométrés avec stress simulé

**2. Restructuration cognitive :**
Remplacez les pensées négatives :
- "Je vais échouer" → "J\'ai bien préparé cet examen"
- "C\'est trop difficile" → "Je peux réussir étape par étape"
- "Tout le monde est meilleur" → "Chacun a ses forces"

**3. Techniques de visualisation :**
- **Visualisation de réussite** : 10 min/jour pendant 3 semaines
- **Répétition mentale** : Visualisez chaque étape de l\'examen
- **Ancrage positif** : Associez un geste à l\'état de confiance

**Programme anti-stress 21 jours :**

**Semaine 1 - Foundation :**
- Technique 4-7-8 matin et soir
- Exercice physique 30 min/jour
- Méditation guidée 10 min

**Semaine 2 - Renforcement :**
- Ajout de la visualisation positive
- Pratique de l\'ancrage 5-4-3-2-1
- Simulation d\'examens

**Semaine 3 - Maîtrise :**
- Techniques combinées
- Tests en conditions réelles
- Optimisation personnalisée

**Alimentation anti-stress :**
- **À favoriser** : Magnésium (chocolat noir, amandes), Oméga-3 (poisson, noix), Vitamine B (œufs, légumes verts)
- **À éviter** : Caféine excessive, sucre raffiné, alcool

**Kit d\'urgence le jour J :**
1. Arrivez 30 minutes en avance
2. Techniques de respiration dans la file d\'attente
3. Affirmations positives personnalisées
4. Lecture rapide des questions avant de commencer
5. Pause respiration toutes les 30 minutes

Ces méthodes, appliquées régulièrement, réduisent le stress d\'examen de 70% selon les études cliniques.',
                'resume' => 'Méthodes psychologiques scientifiquement validées pour transformer le stress des examens en confiance.',
                'tags' => ['stress', 'examens', 'psychologie', 'relaxation', 'confiance'],
                'est_featured' => true,
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop'
            ],
        ];

        // Générer 50 rubriques
        $rubriques = [];
        $baseDate = now()->subMonths(6);
        
        // Répéter les contenus pour atteindre 50 rubriques
        $tousLesContenus = array_merge($actualites, $astucesConseils);
        
        for ($i = 0; $i < 50; $i++) {
            $contenuBase = $tousLesContenus[$i % count($tousLesContenus)];
            $type = $i < 25 ? 'actualite' : 'astuce_conseil';
            
            // Variation du titre pour éviter les doublons
            $suffixe = $i > count($tousLesContenus) - 1 ? ' - Édition ' . ceil(($i + 1) / count($tousLesContenus)) : '';
            
            $rubriques[] = [
                'titre' => $contenuBase['titre'] . $suffixe,
                'slug' => Str::slug($contenuBase['titre'] . $suffixe),
                'contenu' => $contenuBase['contenu'],
                'resume' => $contenuBase['resume'],
                'type_rubrique' => $type,
                'est_publie' => true,
                'est_featured' => $i < 6 ? true : false, // 6 articles en vedette
                'ordre_affichage' => $i + 1,
                'tags' => $contenuBase['tags'],
                'auteur_id' => $adminUser?->id,
                'date_publication' => $baseDate->copy()->addDays(rand(1, 180))->addHours(rand(8, 18)),
                'nb_vues' => rand(50, 1000),
                'image_principale' => $contenuBase['image_url'] ?? null,
                'created_at' => $baseDate->copy()->addDays(rand(1, 180))->addHours(rand(8, 18)),
                'updated_at' => now(),
            ];
        }
        
        // Ajouter quelques rubriques supplémentaires avec titres variés
        $titresSupplementaires = [
            // Actualités supplémentaires
            ['titre' => 'Nouvelle politique de bourses d\'études en Côte d\'Ivoire', 'type' => 'actualite'],
            ['titre' => 'Inauguration de 200 nouvelles salles de classe à Abidjan', 'type' => 'actualite'], 
            ['titre' => 'Formation gratuite aux métiers du numérique pour 1000 jeunes', 'type' => 'actualite'],
            ['titre' => 'Partenariat public-privé pour l\'éducation technique et professionnelle', 'type' => 'actualite'],
            ['titre' => 'Lancement du concours national d\'innovation pédagogique', 'type' => 'actualite'],
            
            // Astuces supplémentaires  
            ['titre' => '10 applications mobiles indispensables pour étudiant', 'type' => 'astuce_conseil'],
            ['titre' => 'Comment optimiser sa mémoire avec la technique des palais mentaux', 'type' => 'astuce_conseil'],
            ['titre' => 'Guide pour bien choisir son orientation après le BAC', 'type' => 'astuce_conseil'],
            ['titre' => 'Méthodes efficaces pour apprendre une langue étrangère rapidement', 'type' => 'astuce_conseil'],
            ['titre' => 'Organisation du bureau et de l\'espace de travail pour étudier', 'type' => 'astuce_conseil'],
        ];
        
        foreach ($titresSupplementaires as $index => $titre) {
            if (count($rubriques) >= 50) break;
            
            $rubriques[] = [
                'titre' => $titre['titre'],
                'slug' => Str::slug($titre['titre']),
                'contenu' => $this->generateContenu($titre['type']),
                'resume' => $this->generateResume($titre['type']),
                'type_rubrique' => $titre['type'],
                'est_publie' => true,
                'est_featured' => false,
                'ordre_affichage' => 50 + $index,
                'tags' => $this->generateTags($titre['type']),
                'auteur_id' => $adminUser?->id,
                'date_publication' => $baseDate->copy()->addDays(rand(1, 180))->addHours(rand(8, 18)),
                'nb_vues' => rand(25, 800),
                'image_principale' => $this->getRandomImageUrl($titre['type']),
                'created_at' => $baseDate->copy()->addDays(rand(1, 180))->addHours(rand(8, 18)),
                'updated_at' => now(),
            ];
        }
        
        // Créer les rubriques en base
        foreach (array_slice($rubriques, 0, 50) as $rubriqueData) {
            $rubrique = Rubrique::create($rubriqueData);
            
            // Ajouter l'image si l'URL est fournie
            if (isset($rubriqueData['image_principale']) && $rubriqueData['image_principale']) {
                try {
                    $rubrique->addMediaFromUrl($rubriqueData['image_principale'])
                             ->toMediaCollection('image_principale');
                } catch (\Exception $e) {
                    // Ignorer l'erreur si l'image ne peut pas être téléchargée
                }
            }
        }
        
        $this->command->info('50 rubriques créées avec succès !');
    }
    
    private function generateContenu($type)
    {
        if ($type === 'actualite') {
            return 'Cette actualité présente les derniers développements dans le secteur éducatif. 

**Points clés :**

1. **Innovation pédagogique** : De nouvelles méthodes d\'enseignement sont testées dans plusieurs établissements pilotes.

2. **Partenariats stratégiques** : Des accords sont signés avec des organismes internationaux pour améliorer la qualité de l\'éducation.

3. **Investissements** : Des fonds importants sont alloués pour moderniser les infrastructures éducatives.

4. **Formation des enseignants** : Un programme de formation continue est mis en place pour accompagner ces changements.

Ces développements s\'inscrivent dans la stratégie nationale d\'amélioration du système éducatif et visent à positionner la Côte d\'Ivoire comme un leader régional en matière d\'éducation.

L\'impact de ces mesures sera évalué sur les 5 prochaines années avec des indicateurs précis de performance et de qualité.';
        }
        
        return 'Ce conseil pratique vous aidera à améliorer significativement votre approche des études.

**Méthode recommandée :**

**Étape 1 : Préparation**
- Analysez votre situation actuelle
- Définissez vos objectifs spécifiques
- Rassemblez les outils nécessaires

**Étape 2 : Mise en pratique**
- Appliquez la technique de façon progressive
- Adaptez la méthode à votre profil
- Mesurez régulièrement vos progrès

**Étape 3 : Optimisation**
- Ajustez la méthode selon vos résultats
- Intégrez les bonnes pratiques dans votre routine
- Partagez votre expérience avec d\'autres

**Résultats attendus :**
En appliquant cette méthode de façon consistante, vous devriez observer des améliorations notables dans les 2-3 semaines suivant sa mise en pratique.

**Conseils supplémentaires :**
- Soyez patient et persévérant
- N\'hésitez pas à demander de l\'aide si nécessaire
- Célébrez vos petites victoires en cours de route';
    }
    
    private function generateResume($type)
    {
        if ($type === 'actualite') {
            $resumes = [
                'Découvrez les dernières innovations qui transforment le paysage éducatif ivoirien.',
                'Une initiative majeure qui va révolutionner l\'apprentissage dans nos établissements.',
                'Les nouvelles mesures qui changeront la donne pour les étudiants et enseignants.',
                'Un partenariat stratégique qui ouvre de nouvelles perspectives éducatives.',
                'Les investissements qui modernisent notre système éducatif national.'
            ];
        } else {
            $resumes = [
                'Conseils pratiques et techniques éprouvées pour optimiser votre réussite scolaire.',
                'Méthodes efficaces pour améliorer vos performances académiques durablement.',
                'Stratégies testées et approuvées pour maximiser votre potentiel d\'apprentissage.',
                'Guide complet pour développer de meilleures habitudes d\'étude.',
                'Techniques innovantes pour transformer votre approche de l\'apprentissage.'
            ];
        }
        
        return $resumes[array_rand($resumes)];
    }
    
    private function generateTags($type)
    {
        if ($type === 'actualite') {
            $tagsPool = ['éducation', 'réforme', 'innovation', 'partenariat', 'investissement', 'modernisation', 'côte d\'ivoire', 'développement', 'qualité', 'excellence'];
        } else {
            $tagsPool = ['conseils', 'méthodes', 'techniques', 'réussite', 'organisation', 'motivation', 'efficacité', 'apprentissage', 'développement personnel', 'productivité'];
        }
        
        // Sélectionner 3-5 tags aléatoirement
        $nombreTags = rand(3, 5);
        return array_rand(array_flip($tagsPool), $nombreTags);
    }
    
    private function getRandomImageUrl($type)
    {
        if ($type === 'actualite') {
            $images = [
                'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=800&h=600&fit=crop'
            ];
        } else {
            $images = [
                'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&h=600&fit=crop'
            ];
        }
        
        return $images[array_rand($images)];
    }
}
