<?php

namespace App\Services;


use App\Entity\Domain;
use App\Repository\DomainRepository;
use App\Repository\LegalInformationRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class XmlService
{
    /**
     * @var LegalInformationRepository
     */
    private $legalInformationRepository;

    /**
     * Todays date
     *
     * @var string $currentDate
     */
    private $currentDate;

    /**
     * xml content
     *
     * @var $content string
     */
    private $content = '';

    /**
     * @var object|\Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;

    /**
     * @var string
     */
    private $project_dir;

    /**
     * @var array|mixed
     */
    private $domains;


    /**
     * XmlService constructor.
     * @param LegalInformationRepository $legalInformationRepository
     * @param Locale $locale
     * @param ContainerInterface $container
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct(LegalInformationRepository $legalInformationRepository, Locale $locale, ContainerInterface $container, DomainRepository $domainRepository)
    {
        $locale->setLocale();
        $this->legalInformationRepository = $legalInformationRepository;
        $this->currentDate = (new \DateTime())->format('Y-m-d');
        $this->router = $container->get('router');
        $this->project_dir = $container->getParameter('kernel.project_dir');
        $this->domains  = $domainRepository->getAll();
    }

    public function run()
    {
        /**
         * @var $domain Domain
         */
        foreach ($this->domains as $domain){
            $this->content .= '<?xml version="1.0" encoding="UTF-8"?>';
            $this->content .= '
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($this->legalInformationRepository->findAllOnlySlug() as $k => $v) {
                $this->makeXml($v['slug'], $domain->getDomain());
            }
            $this->content .= '
</urlset>';

            $file = fopen($this->project_dir . '/public/sitemap_'.$domain->getDomain().'.xml', 'w+');
            fputs($file, $this->content);
            $this->content = '';
        }

    }

    public function makeXml(?string $slug, $domain)
    {
        if ($slug !== null) {
            $this->content .= '
    <url>
        <loc>https://'.$domain . $this->router->generate('_professional_profile') . '/' . $slug . '</loc>
        <lastmod>' . $this->currentDate . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>';
        }
    }
}