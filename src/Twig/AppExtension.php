<?php

namespace App\Twig;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Services\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /** @var ActivityRepository */
    private $activityRepository;

    /** @var CacheInterface */
    private $cache;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Locale
     */
    private $locale;

    /**
     * @param ActivityRepository $activityRepository
     * @param CacheInterface     $cache
     * @param ContainerInterface $container
     * @param RequestStack       $requestStack
     */
    public function __construct(ActivityRepository $activityRepository, CacheInterface $cache, ContainerInterface $container, RequestStack $requestStack)
    {
        $this->activityRepository = $activityRepository;
        $this->cache = $cache;
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('dropdownMenu', [$this, 'dropdownMenu']),
            new TwigFunction('getCache', [$this, 'getCache']),
            new TwigFunction('isCached', [$this, 'isCached']),
            new TwigFunction('setCache', [$this, 'setCache']),
            new TwigFunction('nav', [$this, 'nav']),
            new TwigFunction('routeMatched', [$this, 'routeMatched']),
            new TwigFunction('serialize', [$this, 'serialize']),
            new TwigFunction('getActivity', [$this, 'getActivity'], ['is_safe' => ['html']]),
            new TwigFunction('getActivityobject', [$this, 'getActivityobject'], ['is_safe' => ['html']])
        ];
    }

    public function dropdownMenu(int $activity, ?string $label, $exclude = [], $add = null, $sub = false)
    {

        $activity = $this->activityRepository->findOneBy(['id' => $activity]) ?? null;

        !empty($exclude) ? $exclude = new ArrayCollection($exclude) : $exclude = null;

        return $this->iterateDropdownMenu($activity, $label, $exclude, $add, $sub);
    }

    private function iterateDropdownMenu(Activity $activity, ?string $label, $exclude = null, $add = null, $sub = false)
    {
        $content = null;

        if ($activity !== null) {

            if (!$sub) {
                $content .= '<a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            } else {
                $content .= '<a class="dropdown-item" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            }
            $label !== '' && $label !== null ?
                $content .= $label :
                $content .= $activity->getTranslation();
            $content .= '</a>';

            $content .= '<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">';

            !$activity->getChildren()->isEmpty() ?
                $content .= '<li class="dropdown-item"><a href="' . $this->getHref($activity) .
                    '" title="' . $activity->getTranslation() . '">' . $activity->getTranslation() . '</a></li>' .
                    '<li class="dropdown-divider"></li>'
                : null;

            foreach ($activity->getChildren() as $child) {
                /** @var $child Activity */
                if ($exclude === null || !$exclude->contains($child->getName() ?? $child->getPath())) {
                    if ($child->getChildren()->isEmpty()) {
                        $content .= '<li class="dropdown-item"><a href="' . $this->getHref($child) . '">' . $child->getTranslation() . '</a></li>';
                    } else {
                        $content .= '<li class="dropdown-submenu">';
                        $content .= $this->iterateDropdownMenu($child, null, $exclude, $add, true);
                        $content .= '</li>';
                    }
                }
                if ($add !== null && key($add) === $child->__toString()) {
                    $content .= '<li class="dropdown-submenu">' . $add[key($add)] . '</li>';
                }
            }
            $content .= '</ul>';
        }
        return $content;
    }

    private function getHref(Activity $activity)
    {
        $href = $this->container->get('router')->generate('_search', ['page' => 1]);
        if ((int)$activity->getType() === (int)Activity::PATH) {
            $href .=
                '/' .
                $activity->getTranslation()->getSlug();
        } elseif ((int)$activity->getType() === (int)Activity::ACTIVITY) {
            $href .=
                '/' .
                $activity->getParent()->getTranslation()->getSlug() .
                '/' .
                $activity->getTranslation()->getSlug();
        }
        return $href;
    }

    /**
     * @param $key
     *
     * @return null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getCache($key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param $data
     * @param $key
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setCache($key, $data)
    {
        $this->cache->set($key, $data, 3600);
        return $this->cache->get($key, $data);
    }

    /**
     * @param $key
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function isCached($key): bool
    {
        return $this->cache->has($key);
    }

    public function nav(int $count, ?int $page, $attr)
    {
        $page === null ? $page = 1 : $page = (int)$page;

        $round = ceil(($count / 10) + 1);

        if ($page <= 3) {
            $res = [1, 2, 3, 4, 5];
        } elseif ($page > 3 && $page < $round - 2) {
            $res = [$page - 2, $page - 1, $page, $page + 1, $page + 2];
        } else {
            $res = [$round - 5, $round - 4, $round - 3, $round - 2, $round - 1];
        }

        $content = '<div class="d-flex">';
        $content .= '<nav aria-label="Page navigation example" class="m-auto">';
        $content .= '<ul class="pagination">';

        if ($page - 1 > 0) {
            $params = $attr->get('_route_params');
            $params['page'] = 1;
            $content .= '<li class="page-item">';
            $content .= '<a class="page-link" href="';
            $content .= $this->container->get('router')->generate('_search', $params);
            $content .= '"><<</a></li>';
            unset($params);

            $params = $attr->get('_route_params');
            $params['page'] = $page - 1;
            $content .= '<li class="page-item">';
            $content .= '<a class="page-link" href="';
            $content .= $this->container->get('router')->generate('_search', $params);
            $content .= '"><</a></li>';
            unset($params);
        }

        if ($round < 8) {
            unset($res[6]);
        }
        if ($round < 7) {
            unset($res[5]);
        }
        if ($round < 6) {
            unset($res[4]);
        }
        if ($round < 5) {
            unset($res[3]);
        }
        if ($round < 4) {
            unset($res[2]);
        }
        if ($round < 3) {
            unset($res[1], $res[0]);
        }

        foreach ($res as $r) {
            $params = $attr->get('_route_params');
            $params['page'] = $r;
            $content .= '<li class="page-item ' . ($page !== $r ? '' : 'active') . '">';
            $content .= '<a class="page-link" href="';
            $content .= $this->container->get('router')->generate('_search', $params);
            $content .= '">' . $r . '</a></li>';
            unset($params);
        }

        if ($page + 1 < $round) {
            $params = $attr->get('_route_params');
            $params['page'] = $page + 1;
            $content .= '<li class="page-item">';
            $content .= '<a class="page-link" href="';
            $content .= $this->container->get('router')->generate('_search', $params);
            $content .= '">></a></li>';
            unset($params);

            $params = $attr->get('_route_params');
            $params['page'] = $round - 1;
            $content .= '<li class="page-item">';
            $content .= '<a class="page-link" href="';
            $content .= $this->container->get('router')->generate('_search', $params);
            $content .= '">>></a></li>';
            unset($params);
        }

        $content .= '</ul>';
        $content .= '</nav>';
        $content .= '</div>';

        return $content;
    }

    /**
     * @param String|null $path
     *
     * @return bool
     */
    public function routeMatched(?String $path): bool
    {
        if (preg_match('#^' . $path . '#', $this->requestStack->getMasterRequest()->getRequestUri())) {
            return true;
        }
        return false;
    }


    public function serialize($data)
    {
        return serialize($data);
    }

    /**
     * @param int|null $idParent
     * @param int|null $idChild
     * @param array    $vars
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getActivity(?int $idParent, ?int $idChild = null, $vars = [])
    {
        $key =
            'cache-getActivity-329879-' .
            ($this->requestStack->getMasterRequest()->getLocale() ?? null) .
            ($idParent ?? null) . '-' . ($idChild ?? null);
        if (!$this->cache->has($key)) {
            /**
             * @var $child  Activity
             * @var $parent Activity
             */
            $parent = $this->activityRepository->find($idParent);
            if ($idParent !== null && $idChild !== null) {
                $child = $this->activityRepository->find($idChild);
                $value = '<a ';
                $value .= 'href="' . $this->container->get('router')->generate('_search_geturl', [
                        'typeA' => $parent->getId(),
                        'typeB' => $child->getId()
                    ]) . '"';
                isset($vars['class']) ?
                    $value .= ' class="' . $vars['class'] . '"' :
                    null;
                $value .= '>';
                $value .= ($vars['html'] ?? $child->getTranslation());
                $value .= '</a>';
                $this->cache->set($key, $value);
            } elseif ($idParent !== null) {
                $value = '<a ';
                $value .= 'href="' . $this->container->get('router')->generate('_search_geturl', [
                        'typeA' => $parent->getId()
                    ]) . '">';
                $value .= $parent->getTranslation();
                $value .= '</a>';
                $this->cache->set($key, $value);
            }
        }
        return $this->cache->get($key);
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getActivityobject(?int $id)
    {
        if ($id !== null) {
            $key =
                'cache-getActivityobject-zdz66d5-' .
                ($this->requestStack->getMasterRequest()->getLocale() ?? null) .
                $id;
            if (!$this->cache->has($key)) {
                $this->cache->set($key, $this->activityRepository->find($id), 3600);
            }
        } else {
            return null;
        }
        return $this->cache->get($key);
    }
}