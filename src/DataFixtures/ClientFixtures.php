<?php

namespace App\DataFixtures;

use App\Entity\About;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Location;
use App\Entity\Managers;
use App\Entity\Equipment;
use App\Entity\ServedZone;
use App\Entity\Translation;
use App\Entity\User;
use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use App\Services\ExtractDb;
use App\Entity\CoreBusiness;
use App\Entity\LegalInformation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Psr\SimpleCache\CacheInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{

    private $client_activity = [];

    private $client_servedZone = [];

    /**
     * @var ActivityRepository
     */
    private $aR;

    /**
     * @var ServedZoneRepository
     */
    private $sR;
    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var ExtractDb
     */
    private $extractDb;

    public function __construct(ActivityRepository $aR, ServedZoneRepository $sR, CacheInterface $cache)
    {
        $this->aR = $aR;
        $this->sR = $sR;
        $tools = new Tools();
        $this->client_activity = $tools->getClientActivity();
        $this->client_servedZone = $tools->getClientServedZone();
        $this->cache = $cache;
        $this->extractDb = new ExtractDb();
    }

    private function countI($start)
    {
        $countClient = (int)$this->extractDb->query('SELECT COUNT(*) FROM info_client')[0][0]; // 143 000

        $iStart = 500 * $start; // 0, 500, 1000
        $iMax = ($start * 500) + 499; //499, 999, 1499

        if (((int)$countClient - (int)$iMax) > 500) {
            return [
                'iStart' => $iStart,
                'iMax' => $iMax
            ];
        } else {
            return [
                'iStart' => $iStart,
                'iMax' => $countClient - 1
            ];
        }
    }

    public function load(ObjectManager $manager, int $start = 0)
    {
        $count = $this->countI((int)$start);
        $iStart = $count['iStart'];
        $iMax = $count['iMax'];

        for ($i = $iStart; $i < $iMax; $i++) {
            echo $i . '/' . $iMax . "\n";
            $row = $this->extractDb->query("SELECT * FROM info_client ORDER BY id_client LIMIT 1 OFFSET {$i}")[0];
//            $row = $this->cache->get('info_client_0')[$i];
            $client = new Client();
            $client
                ->setLegalInformation($this->getLegalInformation($row))
                ->setLocation($this->getLocation($row))
                ->setContact($this->getContact($row))
                ->setCoreBusiness($this->getCorebusiness($row))
                ->setUser($this->getUser($row))
                ->setManagers($this->getManagers($row))
                ->setEquipment($this->getEquipment($row))
                ->setAbout($this->getAbout($row));

            $client = $this->addActivities($client, $row);
            $client = $this->addServedZone($client, $row);

            $manager->persist($client);
        }
        $manager->flush();
    }

    private function getLegalInformation($row)
    {
        return (new LegalInformation())
            ->setSiret($row['siret'] ?? null)
            ->setCorporateName(utf8_encode($row['raison_sociale']) ?? null)
            ->setCompanyName(utf8_encode($row['nom_commercial']) ?? null)
            ->setLegalForm(utf8_encode($row['forme_juridique']) ?? null)
            ->setTurnover(utf8_encode($row['chiffre_d_affaire']) ?? null)
            ->setWorkforceNbr(abs((int)utf8_encode($row['effectif_de_l_entreprise'])) ?? null)
            ->setEstablishmentsNbr((int)utf8_encode($row['nombre_d_etablissements']) ?? null);
    }

    private function getLocation($row)
    {
        $location = (new Location())
            ->setAddress(utf8_encode($row['adresse']) ?? null)
            ->setPostalCode(utf8_encode($row['code_postal']) ?? null)
            ->setCity(utf8_encode($row['commune']) ?? null);
        $location = $this->addServedZoneLocation($location, $row);

        return $location;

    }

    private function getContact($row)
    {
        return (new Contact())
            ->setPhone(utf8_encode($row['telephone']) ?? null)
            ->setFax(utf8_encode($row['fax']) ?? null)
            ->setContact(utf8_encode($row['contact']) ?? null)
            ->setWebSite(utf8_encode($row['site_internet']) ?? null);
    }

    private function getCorebusiness($row)
    {
        !empty($this->extractDb->query("SELECT * FROM info_transports WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessTransport = true : $coreBusinessTransport = false;
        !empty($this->extractDb->query("SELECT * FROM info_logistique WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessLogistique = true : $coreBusinessLogistique = false;
        !empty($this->extractDb->query("SELECT * FROM info_affretement WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessAffretement = true : $coreBusinessAffretement = false;
        !empty($this->extractDb->query("SELECT * FROM info_autocars WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessAutocars = true : $coreBusinessAutocars = false;
        !empty($this->extractDb->query("SELECT * FROM info_demenagement WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessDemenagement = true : $coreBusinessDemenagement = false;
        !empty($this->extractDb->query("SELECT * FROM info_entrepots WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessEntrepots = true : $coreBusinessEntrepots = false;
        !empty($this->extractDb->query("SELECT * FROM info_locations WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessLocations = true : $coreBusinessLocations = false;
        !empty($this->extractDb->query("SELECT * FROM info_taxis WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessTaxis = true : $coreBusinessTaxis = false;
        return (new CoreBusiness())
            ->setTransport($coreBusinessTransport)
            ->setLogistics($coreBusinessLogistique)
            ->setCharter($coreBusinessAffretement)
            ->setTravelers($coreBusinessAutocars)
            ->setRelocation($coreBusinessDemenagement)
            ->setStorage($coreBusinessEntrepots)
            ->setRentalWithDriver($coreBusinessLocations)
            ->setTaxis($coreBusinessTaxis);
    }

    private function getUser($row)
    {
        return (new User())
            ->setUsername(utf8_encode($row['e_mail']) ?? null)
            ->setPassword(utf8_encode($row['password']) ?? null);
    }

    private function getManagers($row)
    {
        return (new Managers())
            ->setCompanyManager(utf8_encode($row['responsable_de_l_entreprise_ou_du_site']))
            ->setOperationsManager(utf8_encode($row['responsable_exploitation']))
            ->setSalesManager(utf8_encode($row['responsable_commercial']));
    }

    private function getEquipment($row)
    {
        return (new Equipment())
            ->setVehiclesNbr((int)utf8_encode($row['nombre_de_vehicules']))
            ->setMaterials(utf8_encode($row['materiel_de_l_entreprise']));
    }

    private function getAbout($row)
    {
        return (new About())
            ->setIsoCertification($row['certification_iso'] ?? null)
            ->setSummary($row['___(gratuit)__faites_completer_votre_fiche_ici'] ?? null)
            ->setRangeAction(utf8_encode($row['rayon_d_action']) ?? null)
            ->setServices(utf8_encode($row['services']) ?? null);
    }

    private function getActivities($row)
    {
        $array = [];
        $activiteRow = $this->extractDb->query("SELECT * FROM activite WHERE id_client=" . (int)$row['id_client']);
        if (isset($activiteRow[0])) {
            foreach ($activiteRow[0] as $k => $v) {
                if (is_string($k)) {
                    $array[$k] = $v;
                }
            }
        }
        return $array;
    }

    private function addActivities(Client $client, $row)
    {
        foreach ($this->getActivities($row) as $k => $v) {
            isset($this->client_activity[$k]) && $v ?
                $activity = $this->aR->findOneBy([
                    'name' => $this->client_activity[$k]
                ]) :
                $activity = false;

            if ($activity) {
                $client->addActivity($activity);
            }
        }
        return $client;
    }

    private function getServedZone($row)
    {
        $desservedZones = [];
        $desservedZoneRow = $this->extractDb->query("SELECT * FROM desservir WHERE id_client=" . (int)$row['id_client']);
//        $desservedZoneRow = $this->cache->get('info_client_'.(int)$row['id_client']);
        if (isset($desservedZoneRow[0])) {
            foreach ($desservedZoneRow[0] as $k => $v) {
                if (is_string($k)) {
                    $desservedZones[$k] = $v;
                }
            }
        }
        return $desservedZones;
    }

    private function addServedZone(Client $client, $row)
    {
        foreach ($this->getServedZone($row) as $k => $v) {
            if (isset($this->client_servedZone[$k]) && $v) {
                $region = explode('@', $this->client_servedZone[$k])[1];
                $department = explode('@', $this->client_servedZone[$k])[2];

                /** @var $servedZone ServedZone */
                $servedRegion = $this->sR->findOneBy([
                    'region' => $region
                ]);

                $departments = $servedRegion->getChildren();
                if (is_iterable($departments)) {
                    /** @var $item ServedZone */
                    foreach ($departments as $item) {
                        if ($item->getDepartment() === $department) {
                            $servedZone = $item;
                        }
                    }
                }
            }

            if (isset($servedZone)) {
                $client->addServedZone($servedZone);
            }
        }
        return $client;
    }

    private function getServedZoneLocation($row)
    {
        $desservedZoneRow = $this->extractDb->query("SELECT * FROM rattacher WHERE id_client=" . (int)$row['id_client'])[0];
        return $desservedZoneRow['id_dept'];
    }

    private function addServedZoneLocation(Location $location, $row)
    {
        $match = $this->client_servedZone['dept_' . $this->getServedZoneLocation($row)] ?? null;

        if ($match !== null) {
            $region = explode('@', $match)[1];
            $department = explode('@', $match)[2];

            /** @var $servedZone ServedZone */
            $servedRegion = $this->sR->findOneBy([
                'region' => $region
            ]);

            $departments = $servedRegion->getChildren();
            if (is_iterable($departments)) {
                /** @var $item ServedZone */
                foreach ($departments as $item) {
                    if ($item->getDepartment() === $department) {
                        $servedZone = $item;
                    }
                }
            }
        }

        if (isset($servedZone)) {
            $location->setLocation($servedZone);
        }

        return $location;
    }

    public function getDependencies()
    {
        return [
            ServedZoneFixtures::class,
            ActivitiesFixtures::class
        ];
    }
}