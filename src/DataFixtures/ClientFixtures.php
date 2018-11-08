<?php

namespace App\DataFixtures;

use App\Entity\About;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Location;
use App\Entity\Managers;
use App\Entity\Register;
use App\Entity\Equipment;
use App\Entity\ServedZone;
use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use App\Services\ExtractDb;
use App\Entity\CoreBusiness;
use App\Entity\LegalInformation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{

    private $client_activity = [
        /* industrialTransportOfGoods/perLot */
        'cat_246' => 'inContainer',
        'cat_322' => 'full',
        'cat_323' => 'inBulking',
        'cat_324' => 'inHalfLot',
        'cat_327' => 'textile',
        'cat_330' => 'LessThan3T5',
        /* transportOfGoodsByTank/food */
        'cat_240' => 'liquidFoodCistern',
        'cat_242' => 'foodCisternPulverulent',
        /* transportOfGoodsByTank/non-food */
        'cat_236' => 'liquidNonFoodCistern',
        'cat_237' => 'nonFoodCisternPulverulent',
        'cat_238' => 'dangerousGoodsTank',
        /* transportOfGoods/transportOfGoodsByTruck */
        'cat_245' => 'cerealTipper',
        'cat_247' => 'polybenneSkip',
        'cat_248' => 'tipperTPScrap',
        /* refrigeratedTransportationOfGoods/food */
        'cat_255' => 'refrigeratedHungMeat',
        'cat_256' => 'refrigeratedNegativeTemperature',
        'cat_257' => 'refrigeratedPositiveTemperature',
        /* refrigeratedTransportationOfGoods/non-food */
        'cat_253' => 'refrigerated_Organs_Blood',
        'cat_254' => 'refrigeratedOther',
        /* diverseTransportationOfGoods/liveAnimal */
        'cat_304' => 'horse',
        'cat_314' => 'variousAnimals',
        /* diverseTransportationOfGoods/other */
        'cat_305' => 'funds',
        'cat_306' => 'logs',
        'cat_307' => 'ofInseparableMass',
        'cat_308' => 'ofDangerousGoods',
        'cat_309' => 'of_straw_feed',
        'cat_310' => 'forFragile',
        'cat_311' => 'press_postal',
        'cat_329' => 'furniture',
        'cat_332' => 'dedicatedToPrivate',
        /* diverseTransportationOfGoods/vehicles */
        'cat_286' => 'worksVehicles',
        'cat_287' => 'boats',
        'cat_289' => 'bungalow_mobilhome',
        'cat_290' => 'bikes',
        'cat_291' => 'cars',
        /* diverseTransportationOfGoods/largeDimensions */
        'cat_293' => 'inExceptional',
        /* diverseTransportationOfGoods/nonRoad */
        'cat_299' => 'byAir',
        'cat_300' => 'bySea',
        'cat_301' => 'byRail',
        'cat_302' => 'byInlandWaterway',
        /* transportOfGoods/charter */
        'cat_175' => 'charterClusteringOrFull',
        'cat_176' => 'charteringPerishableFoodstuffs',
        'cat_177' => 'approvedCharteringCustoms',
        'cat_178' => 'airCharter',
        'cat_179' => 'maritimeChartering',
        'cat_180' => 'chateringOther',
        /* transportOfGoods/rentWithDriver */
        'cat_182' => 'rentWithSemiDriverHeavyTrucks',
        'cat_184' => 'rentWithDriverHeavyTrucks',
        'cat_185' => 'rentWithDriverLightVehicles',
        'cat_187' => 'rentWithDriverForkliftTruck',
        'cat_188' => 'rentWithDriverCoaches',
        'cat_189' => 'rentWithDriver_publicWorks',
        /* transportOfGoods/logistic */
        'cat_191' => 'logisticsStockControl',
        'cat_192' => 'logisticsPreparationOfOrders',
        'cat_193' => 'logisticsDistribution',
        'cat_194' => 'logisticsConditioningOfPallets',
        /* transportOfGoods/storage */
        'cat_196' => 'coveredStorage',
        'cat_197' => 'outdoorStorage',
        'cat_198' => 'coldStorage',
        'cat_199' => 'storingOfLiquids',
        'cat_200' => 'storingOfHazardousMaterials',
        'cat_201' => 'secureStorage',
        'cat_202' => 'storageInFurnitureStorage',
        'cat_203' => 'selfStorageStorage',
        /* transportOfGoods/expressTransport */
        'cat_230' => 'urgent_express',
        'cat_231' => 'expressTransport_expressParcel',
        'cat_232' => 'expressTransport_expressDeliveryServices',
        /* mover */
        'cat_205' => 'forIndividuals',
        'cat_206' => 'industrialTransfer',
        'cat_207' => 'administrative',
        'cat_208' => 'ofPiano',
        'cat_209' => 'ofWorksOfArt',
        'cat_210' => 'ofSate',
        'cat_211' => 'inFurnitureStorehouses',
        'cat_212' => 'inSelfStorage',
        /* passengerTransport */
        'cat_214' => 'forGroups',
        'cat_215' => 'schoolTransport',
        'cat_216' => 'regularService',
        'cat_217' => 'travelAgencies',
        'cat_218' => 'packageTour',
        'cat_221' => 'airTravel',
        'cat_222' => 'lightVehicle',
        /* taxi */
        'cat_219' => 'lightVehicleOrBike'
    ];

    private $client_servedZone = [
        /* africa */
        'dept_470' => "africa@centralAfrica@allCountriesOfCentralAfrica",

        'dept_566' => "africa@easternAfrica@allCountriesOfEasternAfrica",

        'dept_476' => "africa@southernAfrica@allCountriesOfSouthernAfrica",

        'dept_412' => "africa@northernAfrica@algeria",
        'dept_591' => "africa@northernAfrica@egypt",
        'dept_288' => "africa@northernAfrica@libya",
        'dept_310' => "africa@northernAfrica@mauritania",
        'dept_427' => "africa@northernAfrica@moroccoRabat",
        'dept_426' => "africa@northernAfrica@moroccoCasablanca",
        'dept_431' => "africa@northernAfrica@moroccoFes",
        'dept_424' => "africa@northernAfrica@moroccoMarrakech",
        'dept_430' => "africa@northernAfrica@moroccoMeknes",
        'dept_428' => "africa@northernAfrica@moroccoOujda",
        'dept_419' => "africa@northernAfrica@moroccoLaayoune",
        'dept_422' => "africa@northernAfrica@moroccoAgadir",
        'dept_433' => "africa@northernAfrica@moroccoTanger",
        'dept_378' => "africa@northernAfrica@tunisiaCentralSousse",
        'dept_374' => "africa@northernAfrica@tunisiaNorthTunis",
        'dept_377' => "africa@northernAfrica@tunisiaSouthGafsa",

        'dept_576' => "africa@westernAfrica@benin",
        'dept_579' => "africa@westernAfrica@burkinaFaso",
        'dept_574' => "africa@westernAfrica@cameroon",
        'dept_572' => "africa@westernAfrica@chad",
        'dept_582' => "africa@westernAfrica@gambia",
        'dept_578' => "africa@westernAfrica@ghana",
        'dept_584' => "africa@westernAfrica@guinea",
        'dept_583' => "africa@westernAfrica@guineaBissau",
        'dept_587' => "africa@westernAfrica@ivoryCoast_central_yamoussoukro",
        'dept_588' => "africa@westernAfrica@ivoryCoast_north_korhogo",
        'dept_571' => "africa@westernAfrica@ivoryCoast_south_abidjan",
        'dept_586' => "africa@westernAfrica@liberia",
        'dept_580' => "africa@westernAfrica@mali",
        'dept_573' => "africa@westernAfrica@niger",
        'dept_575' => "africa@westernAfrica@nigeria",
        'dept_581' => "africa@westernAfrica@senegal",
        'dept_585' => "africa@westernAfrica@sierraLeone",
        'dept_577' => "africa@westernAfrica@togo",

        /* america */
        'dept_604' => "america@canada@calgary",
        'dept_599' => "america@canada@edmonton",
        'dept_596' => "america@canada@princeGeorge_ti",
        'dept_605' => "america@canada@vancouver_lm",
        'dept_606' => "america@canada@victoria_tc",
        'dept_610' => "america@canada@winnipeg",
        'dept_595' => "america@canada@saintJohn",
        'dept_600' => "america@canada@saintJohn_s",
        'dept_594' => "america@canada@halifax",
        'dept_601' => "america@canada@yellowknife",
        'dept_602' => "america@canada@iqaluit",
        'dept_607' => "america@canada@ottawa",
        'dept_569' => "america@canada@toronto",
        'dept_597' => "america@canada@charlottetown",
        'dept_608' => "america@canada@montreal",
        'dept_390' => "america@canada@quebec",
        'dept_609' => "america@canada@regina",
        'dept_598' => "america@canada@sakstoon",
        'dept_603' => "america@canada@whitehorse",

        'dept_545' => "america@centralAmerica@allCountriesOfCentralAmerica",

        'dept_391' => "america@southernAmerica@southAmerica",

        'dept_611' => "america@USA@juneau_anchorage",
        'dept_593' => "america@USA@montgomery_birmingham",
        'dept_613' => "america@USA@littleRock",
        'dept_612' => "america@USA@phoenix",
        'dept_614' => "america@USA@sacramento_losAngeles",
        'dept_617' => "america@USA@denver",
        'dept_618' => "america@USA@hartford_bridgeport",
        'dept_621' => "america@USA@dover_wilmington",
        'dept_622' => "america@USA@tallahassee_jacksonville",
        'dept_623' => "america@USA@atlanta",
        'dept_624' => "america@USA@honolulu",
        'dept_628' => "america@USA@desMoines",
        'dept_625' => "america@USA@boise",
        'dept_626' => "america@USA@springfieldChicago",
        'dept_627' => "america@USA@indianapolis",
        'dept_629' => "america@USA@topekaWichita",
        'dept_630' => "america@USA@frankfortLouisville",
        'dept_631' => "america@USA@batonRouge_newOrleans",
        'dept_634' => "america@USA@boston",
        'dept_633' => "america@USA@annapolis_baltimore",
        'dept_632' => "america@USA@augusta_portland",
        'dept_635' => "america@USA@lansing_detroit",
        'dept_636' => "america@USA@stPaul_minneapolis",
        'dept_638' => "america@USA@jeffersonCity_kansasCity",
        'dept_637' => "america@USA@jackson",
        'dept_639' => "america@USA@helena_billings",
        'dept_615' => "america@USA@raleigh_charlotte",
        'dept_619' => "america@USA@bismarck_fargo",
        'dept_640' => "america@USA@lincoln_omaha",
        'dept_642' => "america@USA@concord_manchester",
        'dept_643' => "america@USA@trenton_newark",
        'dept_644' => "america@USA@santaFe_albuquerque",
        'dept_641' => "america@USA@carsonCity_lasVegas",
        'dept_645' => "america@USA@albany_newYork",
        'dept_646' => "america@USA@columbus",
        'dept_647' => "america@USA@oklahomaCity",
        'dept_648' => "america@USA@salem_portland",
        'dept_649' => "america@USA@harrisburg_philadelphia",
        'dept_650' => "america@USA@providence",
        'dept_616' => "america@USA@columbia",
        'dept_620' => "america@USA@pierre_siouxFalls",
        'dept_651' => "america@USA@nashvilleMemphis",
        'dept_652' => "america@USA@austin_houston",
        'dept_653' => "america@USA@saltLakeCity",
        'dept_655' => "america@USA@richmont_virginiaBeach",
        'dept_654' => "america@USA@montpelier_burlington",
        'dept_656' => "america@USA@olympia_seattle",
        'dept_658' => "america@USA@madison_milwaukee",
        'dept_657' => "america@USA@charleston",
        'dept_659' => "america@USA@cheyenne",

        /* asia */
        'dept_473' => "asia@centralAsia@chine_mongolie",
        'dept_590' => "asia@centralAsia@inde_pakistan",
        'dept_589' => "asia@centralAsia@russia",
        'dept_472' => "asia@farEast@japon_extremeOrient",
        'dept_471' => "asia@middleEast@middleEast",
        'dept_547' => "asia@middleEast@turquie",

        /* easternEurope */
        'dept_454' => "easternEurope@albania@albania",
        'dept_455' => "easternEurope@belarus@belarus",
        'dept_456' => "easternEurope@bosniaAndHerzegovina@bosnieHerzegovine",
        'dept_540' => "easternEurope@bulgaria@bulgaria",
        'dept_460' => "easternEurope@croatia@croatia",
        'dept_370' => "easternEurope@czechR@tchequeR",
        'dept_549' => "easternEurope@estonia@estonia",
        'dept_550' => "easternEurope@hungary@hungary",
        'dept_568' => "easternEurope@kosovo@kosovo",
        'dept_285' => "easternEurope@latvia@latvia",
        'dept_286' => "easternEurope@lithuania@lithuania",
        'dept_462' => "easternEurope@macedonia@macedonia",
        'dept_464' => "easternEurope@moldova@moldova",
        'dept_546' => "easternEurope@montenegro@montenegro",
        'dept_333' => "easternEurope@poland@poland",
        'dept_538' => "easternEurope@romania@bucarest_valachie",
        'dept_534' => "easternEurope@romania@cluj_transilvanie",
        'dept_536' => "easternEurope@romania@constanta_dobrogea",
        'dept_537' => "easternEurope@romania@iasi_moldova",
        'dept_535' => "easternEurope@romania@timisoara_banat",
        'dept_482' => "easternEurope@russia@russia",
        'dept_467' => "easternEurope@serbia@serbia",
        'dept_348' => "easternEurope@slovakia@slovakia",
        'dept_389' => "easternEurope@slovenia@slovenia",
        'dept_468' => "easternEurope@ukraine@ukraine",

        /* oceania */
        'dept_474' => "oceania@oceania@australie",
        'dept_592' => "oceania@oceania@indonesie",
        'dept_475' => "oceania@oceania@oceanie",

        /* andorra */
        'dept_379' => "andorra@andorra@andorra",

        /* austria */
        'dept_155' => "austria@austria@wien",
        'dept_157' => "austria@austria@a'dept_XXXX'_niederosterreich_sanktPolten",
        'dept_154' => "austria@austria@oberOsterreich_linz",
        'dept_151' => "austria@austria@salzburg",
        'dept_150' => "austria@austria@vorarlberg_tirol_innsbruck",
        'dept_156' => "austria@austria@burgenland_eisenstadt",
        'dept_153' => "austria@austria@steiermark_graz",
        'dept_152' => "austria@austria@karnten_klagenfurt",
        'dept_461' => "austria@liechtenstein@liechtenstein",

        /* belgium */
        'dept_166' => "belgium@belgium@bruxelles_brabant_brussels",
        'dept_160' => "belgium@belgium@antwerpen",
        'dept_161' => "belgium@belgium@leuven_hasselt",
        'dept_162' => "belgium@belgium@liege",
        'dept_164' => "belgium@belgium@namur",
        'dept_163' => "belgium@belgium@charleroi_bastogne",
        'dept_165' => "belgium@belgium@mons",
        'dept_159' => "belgium@belgium@brugge",
        'dept_158' => "belgium@belgium@gent",

        /* denmark */
        'dept_171' => "denmark@denmark@frederiksborg",
        'dept_170' => "denmark@denmark@fyn",
        'dept_458' => "denmark@denmark@islande",
        'dept_172' => "denmark@denmark@kobenhavn",
        'dept_167' => "denmark@denmark@nordjylland",
        'dept_169' => "denmark@denmark@ringkobing_ribe_velje_sonderjylland",
        'dept_173' => "denmark@denmark@vestsjaelland_storstrom_roskilde",
        'dept_168' => "denmark@denmark@viborgArhus",

        /* finland */
        'dept_481' => "finland@finlandSuomi@finlande",

        /* France */
        'dept_69' => "France@corsica@ajaccio_bastia_corse",
        'dept_39' => "France@aquitaine@perigueux_dordogne",
        'dept_40' => "France@aquitaine@bordeaux_gironde",
        'dept_41' => "France@aquitaine@montDeMarsan_landes",
        'dept_42' => "France@aquitaine@agen_lotEtGaronne",
        'dept_43' => "France@aquitaine@pau_pyreneesAtlantiques",
        'dept_44' => "France@auvergne@moulins_allier",
        'dept_45' => "France@auvergne@aurillac_cantal",
        'dept_46' => "France@auvergne@lePuyEnVelay_hauteLoire",
        'dept_47' => "France@auvergne@clermontFerrand_puyDeDome",
        'dept_51' => "France@bourgogne@dijon_coteOr",
        'dept_52' => "France@bourgogne@nevers_nievre",
        'dept_53' => "France@bourgogne@macon_saoneEtLoire",
        'dept_54' => "France@bourgogne@auxerre_yonne",
        'dept_55' => "France@bretagne@saintBrieuc_cotesArmor",
        'dept_56' => "France@bretagne@quimper_finistere",
        'dept_57' => "France@bretagne@rennes_illeEtVilaine",
        'dept_58' => "France@bretagne@vannes_morbihan",
        'dept_59' => "France@centre@bourges_cher",
        'dept_60' => "France@centre@chartres_eureEtLoir",
        'dept_61' => "France@centre@chateauroux_indre",
        'dept_62' => "France@centre@tours_indreEtLoire",
        'dept_63' => "France@centre@blois_loirEtCher",
        'dept_64' => "France@centre@orleans_loiret",
        'dept_65' => "France@champagneArdennes@charlevilleMezieres_ardennes",
        'dept_66' => "France@champagneArdennes@troyes_aube",
        'dept_67' => "France@champagneArdennes@chalonsEnChampagne_marne",
        'dept_68' => "France@champagneArdennes@chaumont_hauteMarne",
        'dept_321' => "France@dom_tom_monaco@monaco",
        'dept_543' => "France@dom_tom_monaco@northAtlantic",
        'dept_544' => "France@dom_tom_monaco@indianOcean",
        'dept_542' => "France@dom_tom_monaco@southPacific",
        'dept_71' => "France@francheComte@besancon_doubs",
        'dept_72' => "France@francheComte@lonsLeSaunier_jura",
        'dept_73' => "France@francheComte@vesoul_hauteSaone",
        'dept_74' => "France@francheComte@belfort_territoireDeBelfort",
        'dept_77' => "France@ileDeFrance@paris",
        'dept_78' => "France@ileDeFrance@melun_seineEtMarne",
        'dept_79' => "France@ileDeFrance@versailles_yvelines",
        'dept_80' => "France@ileDeFrance@evry_essonne",
        'dept_81' => "France@ileDeFrance@nanterre_hautsDeSeine",
        'dept_82' => "France@ileDeFrance@bobigny_seineSaintDenis",
        'dept_83' => "France@ileDeFrance@creteil_valDeMarne",
        'dept_84' => "France@ileDeFrance@pontoise_valOise",
        'dept_85' => "France@languedocRoussillon@carcassonne_aude",
        'dept_86' => "France@languedocRoussillon@nimes_gard",
        'dept_87' => "France@languedocRoussillon@montpellier_herault",
        'dept_88' => "France@languedocRoussillon@mende_lozere",
        'dept_89' => "France@languedocRoussillon@perpignan_pyreneesOrientales",
        'dept_90' => "France@limousin@tulle_correze",
        'dept_91' => "France@limousin@gueret_creuse",
        'dept_92' => "France@limousin@limoges_hauteVienne",
        'dept_93' => "France@lorraine@nancy_meurtheEtMoselle",
        'dept_94' => "France@lorraine@barLeDuc_meuse",
        'dept_95' => "France@lorraine@metz_moselle",
        'dept_96' => "France@lorraine@epinal_vosges",
        'dept_97' => "France@midiPyrenees@foix_ariege",
        'dept_98' => "France@midiPyrenees@rodez_aveyron",
        'dept_99' => "France@midiPyrenees@toulouse_hauteGaronne",
        'dept_100' => "France@midiPyrenees@auch_gers",
        'dept_101' => "France@midiPyrenees@cahors_lot",
        'dept_102' => "France@midiPyrenees@tarbes_hautesPyrenees",
        'dept_103' => "France@midiPyrenees@albi_tarn",
        'dept_104' => "France@midiPyrenees@montauban_tarnEtGaronne",
        'dept_105' => "France@nordPasDeCalais@lille_nord",
        'dept_106' => "France@nordPasDeCalais@arras_pasDeCalais",
        'dept_48' => "France@normandie@caen_calvados",
        'dept_75' => "France@normandie@evreux_eure",
        'dept_49' => "France@normandie@saintLo_manche",
        'dept_50' => "France@normandie@alencon_orne",
        'dept_76' => "France@normandie@rouen_seineMaritime",
        'dept_107' => "France@paysDeLaLoire@nantes_loireAtlantique",
        'dept_108' => "France@paysDeLaLoire@angers_maineEtLoire",
        'dept_109' => "France@paysDeLaLoire@laval_mayenne",
        'dept_110' => "France@paysDeLaLoire@leMans_sarthe",
        'dept_111' => "France@paysDeLaLoire@laRocheSurYon_vendee",
        'dept_112' => "France@picardie@laon_aisne",
        'dept_113' => "France@picardie@beauvais_oise",
        'dept_114' => "France@picardie@amiens_somme",
        'dept_115' => "France@poitouCharentes@angoulemeCharente",
        'dept_116' => "France@poitouCharentes@laRochelle_charenteMaritime",
        'dept_117' => "France@poitouCharentes@niort_deuxSevres",
        'dept_118' => "France@poitouCharentes@poitiers_vienne",
        'dept_119' => "France@provenceAlpesCoteAzur@digne_alpesDeHauteProvence",
        'dept_120' => "France@provenceAlpesCoteAzur@gap_hautesAlpes",
        'dept_121' => "France@provenceAlpesCoteAzur@nice_alpesMaritimes",
        'dept_122' => "France@provenceAlpesCoteAzur@marseille_bouchesDuRhone",
        'dept_123' => "France@provenceAlpesCoteAzur@toulon_var",
        'dept_124' => "France@provenceAlpesCoteAzur@avignon_vaucluse",
        'dept_125' => "France@rhoneAlpes@bourgEnBresse_ain",
        'dept_126' => "France@rhoneAlpes@privas_ardeche",
        'dept_127' => "France@rhoneAlpes@valence_drome",
        'dept_128' => "France@rhoneAlpes@grenoble_isere",
        'dept_129' => "France@rhoneAlpes@saintEtienne_loire",
        'dept_130' => "France@rhoneAlpes@lyon_rhone",
        'dept_131' => "France@rhoneAlpes@chambery_savoie",
        'dept_132' => "France@rhoneAlpes@annecy_hauteSavoie",
        'dept_37' => "France@alsace@strasbourg_basRhin",
        'dept_38' => "France@alsace@colmar_hautRhin",

        /* germany */
        'dept_492' => "germany@badenWurttemberg@freiburg",
        'dept_494' => "germany@badenWurttemberg@karlsruhe_mannheim",
        'dept_147' => "germany@badenWurttemberg@stuttgart_pforzheim",
        'dept_493' => "germany@badenWurttemberg@tubingen_ulm",
        'dept_145' => "germany@bayern@ansbach_nuremberg",
        'dept_486' => "germany@bayern@augsburg",
        'dept_491' => "germany@bayern@bayreuth",
        'dept_487' => "germany@bayern@landshut",
        'dept_146' => "germany@bayern@munchen",
        'dept_488' => "germany@bayern@regensburg",
        'dept_490' => "germany@bayern@wurzburg",
        'dept_141' => "germany@berlin@berlin",
        'dept_140' => "germany@hamburg@hamburg",
        'dept_500' => "germany@hessen@darmstadt",
        'dept_148' => "germany@hessen@frankfurt_wiesbaden",
        'dept_502' => "germany@hessen@giessen_marburg",
        'dept_503' => "germany@hessen@kassel",
        'dept_509' => "germany@mecklenburgVorpommern@schwerin",
        'dept_513' => "germany@niedersachsen@braunschweig_gottingen",
        'dept_143' => "germany@niedersachsen@hannover",
        'dept_512' => "germany@niedersachsen@luneburg",
        'dept_511' => "germany@niedersachsen@oldenburg_osnabruck",
        'dept_515' => "germany@nordrheinWestfalen@arnsberg_dortmund",
        'dept_514' => "germany@nordrheinWestfalen@bonn",
        'dept_516' => "germany@nordrheinWestfalen@detmold_paderborn",
        'dept_142' => "germany@nordrheinWestfalen@dusseldorf",
        'dept_149' => "germany@nordrheinWestfalen@koln",
        'dept_517' => "germany@nordrheinWestfalen@munster",
        'dept_499' => "germany@rheinlandPfalz@koblenz",
        'dept_498' => "germany@rheinlandPfalz@mainz",
        'dept_496' => "germany@rheinlandPfalz@neustadt_kaiserslautern",
        'dept_497' => "germany@rheinlandPfalz@trier",
        'dept_495' => "germany@saarland@saarbrucken",
        'dept_485' => "germany@sachsen@chemnitz_zwickau",
        'dept_144' => "germany@sachsen@dresden",
        'dept_484' => "germany@sachsen@leipzig",
        'dept_506' => "germany@sachsenAnhalt@dessau",
        'dept_507' => "germany@sachsenAnhalt@halle",
        'dept_505' => "germany@sachsenAnhalt@magdeburg",
        'dept_510' => "germany@schleswigHolstein@kiel",
        'dept_504' => "germany@thuringen@erfurt",
        'dept_508' => "germany@brandenburg@potsdam",
        'dept_483' => "germany@bremen@bremen",

        /* greece */
        'dept_558' => "greece@greece@chypre",
        'dept_259' => "greece@greece@grece",
        'dept_463' => "greece@greece@malte",

        /* ireland */
        'dept_264' => "ireland@ireland@cork_munster",
        'dept_263' => "ireland@ireland@dublin_leinster",
        'dept_262' => "ireland@ireland@galway_connaught",
        'dept_261' => "ireland@ireland@ulster",

        /* italy */
        'dept_281' => "italy@basilicata@potenza",
        'dept_282' => "italy@calabria@catanzaro",
        'dept_518' => "italy@calabria@cosenza",
        'dept_279' => "italy@campania@napoli",
        'dept_519' => "italy@campania@salerno",
        'dept_272' => "italy@emiliaRomagna@bologna",
        'dept_529' => "italy@emiliaRomagna@ferrara",
        'dept_528' => "italy@emiliaRomagna@parma",
        'dept_270' => "italy@friuliVeneziaGiulia@udine",
        'dept_520' => "italy@lazio_vaticano@latina",
        'dept_276' => "italy@lazio_vaticano@roma",
        'dept_469' => "italy@lazio_vaticano@vatican",
        'dept_268' => "italy@liguria@genova",
        'dept_525' => "italy@lombardia@brescia",
        'dept_267' => "italy@lombardia@milano",
        'dept_275' => "italy@marche_saintMarin@ancona",
        'dept_466' => "italy@marche_saintMarin@rsm_saintMarin",
        'dept_278' => "italy@molise@campobasso",
        'dept_522' => "italy@piemonte@piemonteEast",
        'dept_523' => "italy@piemonte@cuneo",
        'dept_266' => "italy@piemonte@torino",
        'dept_280' => "italy@puglia@bari",
        'dept_283' => "italy@sardegna@cagliari",
        'dept_521' => "italy@sardegna@sassari",
        'dept_530' => "italy@sicilia@messina",
        'dept_284' => "italy@sicilia@palermo",
        'dept_273' => "italy@toscana@firenze",
        'dept_531' => "italy@toscana@pisa",
        'dept_570' => "italy@toscana@siena",
        'dept_269' => "italy@trentinoAltoAdige@trento",
        'dept_274' => "italy@umbria@perugia",
        'dept_265' => "italy@valAoste@aosta",
        'dept_271' => "italy@veneto@padova_venezia",
        'dept_527' => "italy@veneto@treviso_belluno",
        'dept_526' => "italy@veneto@verona_vicenza",
        'dept_277' => "italy@abruzzo@aquila",

        /* luxembourg */
        'dept_287' => "luxembourg@luxembourg@luxembourg",

        /* netherlands */
        'dept_479' => "netherlands@netherlands@amsterdam_zaanstad",
        'dept_563' => "netherlands@netherlands@haarlem_denHaag",
        'dept_326' => "netherlands@netherlands@rotterdam_utrecht",
        'dept_328' => "netherlands@netherlands@breda_middelburg",
        'dept_329' => "netherlands@netherlands@eindhoven_tilburg",
        'dept_330' => "netherlands@netherlands@maastricht_arnhem",
        'dept_327' => "netherlands@netherlands@apeldoorn_enschede",
        'dept_324' => "netherlands@netherlands@zwolle_leeuwarden",
        'dept_323' => "netherlands@netherlands@groningue_assen",

        /* norway */
        'dept_322' => "norway@norway@norvege",

        /* portugal */
        'dept_345' => "portugal@portugal@lisboa",
        'dept_344' => "portugal@portugal@santarem",
        'dept_343' => "portugal@portugal@coimbra_viseu",
        'dept_340' => "portugal@portugal@porto",
        'dept_564' => "portugal@portugal@braganca_vilaReal",
        'dept_565' => "portugal@portugal@castelBranco",
        'dept_346' => "portugal@portugal@beja",
        'dept_347' => "portugal@portugal@faro",
        'dept_341' => "portugal@portugal@lesAcores_madeira",

        /* spain */
        'dept_191' => "spain@basqueCountry_euskadi@araba",
        'dept_181' => "spain@basqueCountry_euskadi@gipuzkoa",
        'dept_182' => "spain@basqueCountry_euskadi@nafarroa",
        'dept_180' => "spain@basqueCountry_euskadi@bizkaiaBilbao",
        'dept_183' => "spain@aragon@huesca",
        'dept_200' => "spain@aragon@teruel",
        'dept_188' => "spain@aragon@zaragosa",
        'dept_178' => "spain@asturias@oviedo",
        'dept_179' => "spain@cantabria@santander",
        'dept_210' => "spain@castilla_laMancha@albacete",
        'dept_209' => "spain@castilla_laMancha@ciudadReal",
        'dept_203' => "spain@castilla_laMancha@cuenca",
        'dept_199' => "spain@castilla_laMancha@guadalajara",
        'dept_208' => "spain@castilla_laMancha@toledo",
        'dept_205' => "spain@castillaYLeon@avila",
        'dept_192' => "spain@castillaYLeon@burgos",
        'dept_194' => "spain@castillaYLeon@leon",
        'dept_193' => "spain@castillaYLeon@palencia",
        'dept_206' => "spain@castillaYLeon@salamanca",
        'dept_198' => "spain@castillaYLeon@segovia",
        'dept_189' => "spain@castillaYLeon@soria",
        'dept_197' => "spain@castillaYLeon@valladolid",
        'dept_196' => "spain@castillaYLeon@zamora",
        'dept_185' => "spain@catalunya@barcelona",
        'dept_186' => "spain@catalunya@girona",
        'dept_184' => "spain@catalunya@lleida",
        'dept_187' => "spain@catalunya@tarragona",
        'dept_384' => "spain@balears@mallorca",
        'dept_218' => "spain@extremadura@badajoz",
        'dept_207' => "spain@extremadura@caceres",
        'dept_174' => "spain@galicia@aCoruna",
        'dept_176' => "spain@galicia@lugo",
        'dept_177' => "spain@galicia@ourense",
        'dept_175' => "spain@galicia@vigo",
        'dept_190' => "spain@laRioja@logrono",
        'dept_204' => "spain@madrid@madrid",
        'dept_213' => "spain@murcia@murcia",
        'dept_212' => "spain@theCanaryIslands@lasPalmas",
        'dept_385' => "spain@theCanaryIslands@santaCruzDeTenerife",
        'dept_211' => "spain@valencia@alacant",
        'dept_201' => "spain@valencia@castelloDeLaPlana",
        'dept_202' => "spain@valencia@valencia",
        'dept_214' => "spain@andalucia@almeria",
        'dept_222' => "spain@andalucia@cadiz",
        'dept_216' => "spain@andalucia@cordoba",
        'dept_215' => "spain@andalucia@granada",
        'dept_219' => "spain@andalucia@huelva",
        'dept_217' => "spain@andalucia@jaen",
        'dept_221' => "spain@andalucia@malaga",
        'dept_220' => "spain@andalucia@sevilla",

        /* sweden */
        'dept_356' => "sweden@sweden@sweden",

        /* switzerland */
        'dept_358' => "switzerland@switzerland@geneve_lausanne_fribourg_sion",
        'dept_357' => "switzerland@switzerland@neuchatel_delemont",
        'dept_359' => "switzerland@switzerland@bern",
        'dept_360' => "switzerland@switzerland@basel_soleure_liestal",
        'dept_361' => "switzerland@switzerland@aarau",
        'dept_362' => "switzerland@switzerland@luzern_zug_schwyz_altdorf_stans_sarnen",
        'dept_363' => "switzerland@switzerland@chur",
        'dept_364' => "switzerland@switzerland@zurich_schaffhausen_frauenfeld_glarus",
        'dept_365' => "switzerland@switzerland@stGallen_appenzeil_herisau",

        /* unitedKingdom */
        'dept_250' => "unitedKingdom@eastAnglia@cambridge",
        'dept_251' => "unitedKingdom@eastAnglia@ipswich",
        'dept_252' => "unitedKingdom@eastAnglia@norwich",
        'dept_244' => "unitedKingdom@eastMidlands@derby",
        'dept_246' => "unitedKingdom@eastMidlands@leicester",
        'dept_248' => "unitedKingdom@eastMidlands@northampton",
        'dept_245' => "unitedKingdom@eastMidlands@nottingham",
        'dept_253' => "unitedKingdom@london@london",
        'dept_231' => "unitedKingdom@northEast@middlesbrough",
        'dept_233' => "unitedKingdom@northEast@newcastleUponTyne",
        'dept_237' => "unitedKingdom@northWest@carlisle",
        'dept_238' => "unitedKingdom@northWest@liverpool",
        'dept_232' => "unitedKingdom@northWest@manchester",
        'dept_254' => "unitedKingdom@southEast@brighton",
        'dept_560' => "unitedKingdom@southEast@oxford",
        'dept_559' => "unitedKingdom@southEast@reading",
        'dept_255' => "unitedKingdom@southEast@southampton",
        'dept_258' => "unitedKingdom@southWest@bournemouth",
        'dept_257' => "unitedKingdom@southWest@bristol",
        'dept_256' => "unitedKingdom@southWest@gloucester",
        'dept_242' => "unitedKingdom@southWest@plymouth",
        'dept_247' => "unitedKingdom@westMidlands@birmingham_warwick",
        'dept_249' => "unitedKingdom@westMidlands@hereford_worcester",
        'dept_243' => "unitedKingdom@westMidlands@stafford",
        'dept_235' => "unitedKingdom@yorkshireAndHumberside@kingstonUponHull",
        'dept_236' => "unitedKingdom@yorkshireAndHumberside@leeds",
        'dept_234' => "unitedKingdom@yorkshireAndHumberside@york",
        'dept_225' => "unitedKingdom@northernIreland@belfast",
        'dept_227' => "unitedKingdom@scotland@aberdeenDundee",
        'dept_229' => "unitedKingdom@scotland@edimbourg",
        'dept_230' => "unitedKingdom@scotland@glasgow",
        'dept_226' => "unitedKingdom@scotland@inverness",
        'dept_241' => "unitedKingdom@walesCymru@cardiff_powys",
        'dept_240' => "unitedKingdom@walesCymru@swansea_ceredigion",
        'dept_239' => "unitedKingdom@walesCymru@wrexham_gwynedd"
    ];

    /**
     * @var ActivityRepository
     */
    private $aR;

    /**
     * @var ServedZoneRepository
     */
    private $sR;

    public function __construct(ActivityRepository $aR, ServedZoneRepository $sR)
    {
        $this->aR = $aR;
        $this->sR = $sR;
    }

    public function load(ObjectManager $manager)
    {
//        $countClient = ExtractDb::query('SELECT COUNT(*) FROM info_client')[0][0];
        $iMax = 1000;
        for ($i = 0; $i < $iMax; $i++) {
            echo(($i + 1) . '/' . $iMax . "\n");
            $row = ExtractDb::query("SELECT * FROM info_client ORDER BY id_client LIMIT 1 OFFSET {$i}")[0];

            $client = new Client();
            $client
                ->setLegalInformation($this->getLegalInformation($row))
                ->setLocation($this->getLocation($row))
                ->setContact($this->getContact($row))
                ->setCoreBusiness($this->getCorebusiness($row))
                ->setRegister($this->getRegister($row))
                ->setManagers($this->getManagers($row))
                ->setEquipment($this->getEquipment($row))
                ->setAbout($this->getAbout($row));

            $client = $this->addActivities($client, $row);
            $client = $this->addServedZone($client, $row);

            $manager->persist($client);

            if ($i % 500 === 0) {
                $manager->flush();
            }
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
        return (new Location())
            ->setAddress(utf8_encode($row['adresse']) ?? null)
            ->setPostalCode(utf8_encode($row['code_postal']) ?? null)
            ->setCity(utf8_encode($row['commune']) ?? null)
            ->setLocation('à définir');
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
        !empty(ExtractDb::query("SELECT * FROM info_transports WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessTransport = true : $coreBusinessTransport = false;
        !empty(ExtractDb::query("SELECT * FROM info_logistique WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessLogistique = true : $coreBusinessLogistique = false;
        !empty(ExtractDb::query("SELECT * FROM info_affretement WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessAffretement = true : $coreBusinessAffretement = false;
        !empty(ExtractDb::query("SELECT * FROM info_autocars WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessAutocars = true : $coreBusinessAutocars = false;
        !empty(ExtractDb::query("SELECT * FROM info_demenagement WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessDemenagement = true : $coreBusinessDemenagement = false;
        !empty(ExtractDb::query("SELECT * FROM info_entrepots WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessEntrepots = true : $coreBusinessEntrepots = false;
        !empty(ExtractDb::query("SELECT * FROM info_locations WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessLocations = true : $coreBusinessLocations = false;
        !empty(ExtractDb::query("SELECT * FROM info_taxis WHERE id_client =" . (int)$row['id_client'])) ? $coreBusinessTaxis = true : $coreBusinessTaxis = false;
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

    private function getRegister($row)
    {
        return (new Register())
            ->setMail(utf8_encode($row['e_mail']) ?? null)
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
        $activiteRow = ExtractDb::query("SELECT * FROM activite WHERE id_client=" . (int)$row['id_client']);
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
        $desservedZoneRow = ExtractDb::query("SELECT * FROM desservir WHERE id_client=" . (int)$row['id_client']);
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

                /** @var $servedZone ServedZone*/
                $servedRegion = $this->sR->findOneBy([
                    'region' => $region
                ]);

                $departments = $servedRegion->getChildren();
                if (is_iterable($departments)){
                    /** @var $item ServedZone */
                    foreach ($departments as $item){
                        if ($item->getDepartment() === $department){
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

    public function getDependencies()
    {
        return [
            ServedZoneFixtures::class,
            ActivitiesFixtures::class
        ];
    }
}