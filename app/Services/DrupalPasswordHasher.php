<?php

namespace App\Services;

/**
 * Réimplémentation fidèle de l'algorithme phpass utilisé par Drupal 7
 * (includes/password.inc, fonctions _password_crypt / user_check_password),
 * pour vérifier les mots de passe importés depuis l'ancien site sans les
 * connaître en clair. Ne gère que le format "$S$..." (SHA-512), seul format
 * présent dans l'export récupéré — pas besoin de gérer $H$/$P$ (MD5, encore
 * plus ancien) ni le préfixe "U$" (comptes déjà migrés depuis Drupal 6).
 *
 * Algorithme original : "Portable PHP password hashing framework" par
 * Solar Designer, repris par Drupal sous licence GPL — domaine public pour
 * l'algorithme lui-même.
 */
class DrupalPasswordHasher
{
    private const ITOA64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private const HASH_LENGTH = 55;
    private const MIN_HASH_COUNT = 7;
    private const MAX_HASH_COUNT = 30;

    /**
     * Le hash importé commence-t-il par le marqueur Drupal 7 (SHA-512) ?
     */
    public function isDrupalHash(?string $hash): bool
    {
        return $hash !== null && str_starts_with($hash, '$S$');
    }

    /**
     * Vérifie un mot de passe en clair contre un hash Drupal 7 "$S$...".
     */
    public function verify(string $password, string $storedHash): bool
    {
        if (!$this->isDrupalHash($storedHash) || strlen($password) > 512) {
            return false;
        }

        $computed = $this->crypt($password, $storedHash);

        return $computed !== false && hash_equals($storedHash, $computed);
    }

    private function crypt(string $password, string $setting): string|false
    {
        $setting = substr($setting, 0, 12);

        if ($setting[0] !== '$' || $setting[2] !== '$') {
            return false;
        }

        $countLog2 = strpos(self::ITOA64, $setting[3]);
        if ($countLog2 === false || $countLog2 < self::MIN_HASH_COUNT || $countLog2 > self::MAX_HASH_COUNT) {
            return false;
        }

        $salt = substr($setting, 4, 8);
        if (strlen($salt) !== 8) {
            return false;
        }

        $count = 1 << $countLog2;
        $hash = hash('sha512', $salt . $password, true);
        do {
            $hash = hash('sha512', $hash . $password, true);
        } while (--$count);

        $len = strlen($hash);
        $output = $setting . $this->base64Encode($hash, $len);
        $expected = 12 + (int) ceil(8 * $len / 6);

        return strlen($output) === $expected ? substr($output, 0, self::HASH_LENGTH) : false;
    }

    private function base64Encode(string $input, int $count): string
    {
        $output = '';
        $i = 0;

        do {
            $value = ord($input[$i++]);
            $output .= self::ITOA64[$value & 0x3f];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= self::ITOA64[($value >> 6) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= self::ITOA64[($value >> 12) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            $output .= self::ITOA64[($value >> 18) & 0x3f];
        } while ($i < $count);

        return $output;
    }
}
