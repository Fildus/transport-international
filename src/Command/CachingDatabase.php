<?php

namespace App\Command;

use App\DataFixtures\ActivitiesFixtures;
use App\DataFixtures\ClientFixtures;
use App\DataFixtures\DomainsFixtures;
use App\DataFixtures\ServedZoneFixtures;
use App\Repository\ActivityRepository;
use App\Repository\DomainRepository;
use App\Repository\ServedZoneRepository;
use App\Services\Slug;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CachingDatabase extends Command
{

    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var ActivityRepository
     */
    private $aR;
    /**
     * @var ServedZoneRepository
     */
    private $sR;
    /**
     * @var Slug
     */
    private $slug;

    public function __construct(
        ActivityRepository $aR,
        ServedZoneRepository $sR,
        CacheInterface $cache,
        ObjectManager $objectManager,
        Slug $slug
    )
    {
        parent::__construct();
        $this->cache = $cache;
        $this->objectManager = $objectManager;
        $this->aR = $aR;
        $this->sR = $sR;
        $this->slug = $slug;
    }

    protected function configure()
    {
        $this
            ->setName('CODB')
            ->addArgument('number', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ((int)$input->getArgument('number') === 1000) {
            (new ServedZoneFixtures($this->sR, $this->slug))->load($this->objectManager);
            (new ActivitiesFixtures($this->aR, $this->slug))->load($this->objectManager);
            (new DomainsFixtures($this->aR))->load($this->objectManager);
        } elseif ((int)$input->getArgument('number') >= 0 && (int)$input->getArgument('number') !== 1000) {
            (new ClientFixtures($this->aR, $this->sR, $this->cache))->load($this->objectManager, (int)$input->getArgument('number') ?? 0);
        }
    }

}