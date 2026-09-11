<?php

namespace App\Exceptions;

use Exception;

/**
 * Regroupe toute erreur survenant pendant l'analyse IA d'un sujet (réseau,
 * réponse Gemini invalide/non-JSON, quota dépassé...) sous un seul type,
 * pour que les contrôleurs n'aient qu'un seul catch à gérer.
 */
class AiAnalysisException extends Exception
{
    //
}
