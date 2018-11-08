<?php
/**
 * @author David
 * Sert à récupérer les informations sur l'ancienne base de données
 */

namespace App\Services;

use \PDO;

class ExtractDb
{
    /**
     * Tables les plus importantes
     */
    const ACTIVITE = 'activite';
    const ALERTE = 'alerte';
    const CATEGORIE = 'categorie';
    const CLIENT = 'info_client';
    const COMMENTAIRE = 'commentaire';
    const COMPTEUR = 'compteur';
    const DEPARTEMENT = 'departement';
    const DESSERVIR = 'desservir';
    const INFO_AFFRETEMENT = 'info_affretement';
    const INFO_AUTOCARS = 'info_autocars';
    const INFO_CLIENT = 'info_client';
    const INFO_DEMENAGEMENT = 'info_demenagement';
    const INFO_ENTREPOTS = 'info_entrepots';
    const INFO_LOCATIONS = 'info_locations';
    const INFO_LOGISTIQUE = 'info_logistique';
    const INFO_TAXIS = 'info_taxis';
    const INFO_TRANSPORTS = 'info_transports';
    const LANGUES = 'langues';
    const NAF = 'naf';
    const NDD = 'ndd';
    const PAYS = 'pays';
    const RECEVOIR = 'recevoir';
    const REGION = 'region';

    /**
     * Methodes pour fetch la bdd
     */
    const FETCHALL = 'fetchAll';
    const FETCH = 'fetch';
    const FETCHOBJECT = 'fetchObject';

    /**
     * Methods pour dump
     */
    const DUMP = 'dump';
    const DD = 'dd';

    public static function pdo($dbName)
    {
        return new PDO('mysql:dbname='.$dbName.';host=127.0.0.1', 'david', 'beepbeep');
    }

    public static function extract(string $table, string $dump = null, ?int $rows = 100, ?string $method = 'fetchAll', $dbName = 'ti_existant_complet')
    {
        $rows === null ? $rows = 100 : null;
        $rows === 0 ? $sqlLimit = null : $sqlLimit = 'LIMIT ' . $rows;

        $res = self::pdo($dbName)->query("SELECT * FROM {$table} {$sqlLimit}");

        if ($method === 'fetchAll' || $method === null) {
            $res = $res->fetchAll();
        } elseif ($method === 'fetch') {
            $res = $res->fetch();
        } elseif ($method ==='fetchObject') {
            $res = $res->fetchObject();
        }

        if ($dump === 'dump') {
            dump($res);
        } elseif ($dump === 'dd') {
            dd($res);
        }
        return $res;
    }

    public static function query(string $query, $fetch = 'fetch', ?string $dbName = 'ti_existant_complet')
    {
        if ($fetch === 'fetch'){
            return self::pdo($dbName)->query($query)->fetchAll();
        }elseif ($fetch === 'object'){
            return self::pdo($dbName)->query($query)->fetchObject();
        }
        return self::pdo($dbName)->query($query);
    }

}