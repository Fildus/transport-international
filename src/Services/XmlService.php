<?php

namespace App\Services;


use App\Entity\Domain;
use App\Repository\DomainRepository;
use App\Repository\LegalInformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class XmlService extends AbstractController
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
     * @var $siteMapdomains array
     */
    private $siteMapdomains;


    /**
     * XmlService constructor.
     *
     * @param LegalInformationRepository $legalInformationRepository
     * @param Locale                     $locale
     * @param ContainerInterface         $container
     *
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
        $this->domains = $domainRepository->getAll();
    }

    public function run()
    {
        /**
         * @var $domain Domain
         */
        foreach ($this->domains as $domain) {
            $convertLang = [
                'fr-BE' => 'fr',
                'fr-CH' => 'fr',
                'en-GB' => 'en',
                'gb' => 'en'
            ];

            $this->content .= '<?xml version="1.0" encoding="UTF-8"?>';
            $this->content .= '
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($this->legalInformationRepository->findAllOnlySlug() as $k => $v) {

                array_key_exists($domain->getLang(), $convertLang) === true ?
                    $lang = $convertLang[$domain->getLang()] :
                    $lang = $domain->getLang();

                $this->makeXml($v['slug'], $domain->getDomain(), $lang);
            }
            $this->content .= '
</urlset>';

            $siteMapdomain = $domain->getDomain();
            $this->siteMapdomains[] = $siteMapdomain;
            $siteMapName = 'sitemap_' . $siteMapdomain . '.xml';

            $file = fopen($this->project_dir . '/public/' . $siteMapName, 'w+');
            fputs($file, $this->content);
            $this->content = '';
        }

        $this->makeMainXml();
        $this->makeRobot();

    }

    public function makeXml(?string $slug, $domain, $lang)
    {
        if ($slug !== null) {
            $this->content .= '
    <url>
        <loc>https://www.' . $domain . $this->router->generate('_professional_profile.' . $lang) . '/' . $slug . '</loc>
        <lastmod>' . $this->currentDate . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>';
        }
    }

    private function makeMainXml()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($this->siteMapdomains as $item) {
            $content .= '
    <sitemap>
        <loc>https://www.' . $item . '/sitemap_' . $item . '.xml</loc>
        <lastmod>' . $this->currentDate . '</lastmod>
    </sitemap>';
        }
        $content .= '
</sitemapindex>';
        $file = fopen($this->project_dir . '/public/sitemap.xml', 'w+');
        fputs($file, $content);
    }

    private function makeRobot()
    {
        $lang = ['fr', 'en', 'es', 'de', 'it', 'pt', 'be', 'ad', 'ro', 'ma', 'ci'];
        $content = 'User-agent: *';
        $content .= "\n";
        foreach ($lang as $l) {
            $content .= 'Disallow: ' . $this->generateUrl('_login.' . $l);
            $content .= "\n";
        }
        foreach ($lang as $l) {
            $content .= 'Disallow: ' . $this->generateUrl('_register.' . $l);
            $content .= "\n";
        }
        foreach ($lang as $l) {
            $content .= 'Disallow: ' . $this->generateUrl('account_legalInformation.' . $l);
            $content .= "\n";
        }
        foreach ($this->siteMapdomains as $item) {
            $content .= 'sitemap: ';
            $content .= 'https://www.' . $item . '/sitemap.xml';
            $content .= "\n";
        }
        $file = fopen($this->project_dir . '/public/robots.txt', 'w+');
        fputs($file, $content);
    }
}