<?php
// filepath: c:\laragon\www\refonte_maxisujet\app\Services\PointsService.php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class PointsService
{
    // Points accordés pour différentes actions
    const POINTS_INSCRIPTION = 50;
    const POINTS_PUBLICATION_SUJET = 100;
    const POINTS_CONNEXION_QUOTIDIENNE = 10;

    /**
     * Ajouter des points à un utilisateur
     */
    public function addPoints(User $user, int $points, string $reason = '')
    {
        $user->increment('points', $points);
        
        // Optionnel : enregistrer l'historique des points
        // PointHistory::create([
        //     'user_id' => $user->id,
        //     'points' => $points,
        //     'reason' => $reason,
        //     'created_at' => now(),
        // ]);
        
        return $user->fresh();
    }

    /**
     * Points pour l'inscription
     */
    public function giveRegistrationPoints(User $user)
    {
        return $this->addPoints($user, self::POINTS_INSCRIPTION, 'Inscription sur la plateforme');
    }

    /**
     * Points pour la publication d'un sujet
     */
    public function givePublicationPoints(User $user)
    {
        return $this->addPoints($user, self::POINTS_PUBLICATION_SUJET, 'Publication d\'un sujet');
    }

    /**
     * Points pour la connexion quotidienne
     */
    public function giveDailyLoginPoints(User $user)
    {
        // Vérifier si l'utilisateur s'est connecté aujourd'hui
        $lastLogin = $user->last_login_at ? Carbon::parse($user->last_login_at) : null;
        $today = Carbon::today();

        // Si c'est la première connexion ou si la dernière connexion était hier ou avant
        if (!$lastLogin || $lastLogin->diffInDays($today) >= 1) {
            // Mettre à jour la date de dernière connexion
            $user->update(['last_login_at' => now()]);
            
            // Donner les points
            return $this->addPoints($user, self::POINTS_CONNEXION_QUOTIDIENNE, 'Connexion quotidienne');
        }

        return $user;
    }

    /**
     * Obtenir le total des points d'un utilisateur
     */
    public function getUserPoints(User $user)
    {
        return $user->points ?? 0;
    }
}