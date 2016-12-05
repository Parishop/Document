<?php
namespace Parishop;

/**
 * Class Document
 * @package Parishop
 * @since   1.0
 */
class Document implements \PHPixie\Template\Extensions\Extension
{
    /**
     * @var \PHPixie\Framework\Builder
     * @since 1.0.1
     */
    protected $builder;

    /**
     * @var string
     * @since 1.0
     */
    protected $title;

    /**
     * @var string
     * @since 1.0
     */
    protected $description;

    /**
     * @var array
     * @since 1.0
     */
    protected $metaNames = [];

    /**
     * @var array
     * @since 1.0
     */
    protected $metaProperties = [];

    /**
     * @var array
     * @since 1.0
     */
    protected $metaHttp = [];

    /**
     * @var array
     * @since 1.0
     */
    protected $links = [];

    /**
     * @var array
     * @since 1.0
     */
    protected $scripts = [];

    /**
     * @param \PHPixie\Framework\Builder $builder
     * @since 1.0.1
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $href
     * @since 1.0
     */
    public function addLess($href)
    {
        $this->addLink($href, 'stylesheet/less', 'text/css');
    }

    /**
     * @param string $href
     * @param string $rel
     * @param string $type
     * @since 1.0
     */
    public function addLink($href, $rel, $type = null)
    {
        $attributes = [];
        if($type !== null) {
            $attributes['type'] = $this->clear($type);
        }
        $this->setLink($this->clear($href), array_merge(['rel' => $this->clear($rel)], $attributes));
    }

    /**
     * @param string $name
     * @param string $value
     * @since 1.0
     */
    public function addMetaHttp($name, $value)
    {
        if($name && $value) {
            $this->metaHttp[$this->clear($name)] = $this->clear($value);
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @since 1.0
     */
    public function addMetaName($name, $value)
    {
        if($name && $value) {
            $this->metaNames[$this->clear($name)] = $this->clear($value);
        }
    }

    /**
     * @param string $href
     * @since 1.0
     */
    public function addScript($href)
    {
        if($href) {
            $this->scripts[$this->clear($href)] = $this->clear($href);
        }
    }

    /**
     * @param string $href
     * @since 1.0
     */
    public function addStylesheet($href)
    {
        $this->addLink($href, 'stylesheet', 'text/css');
    }

    /**
     * @return array
     * @since 1.0
     */
    public function aliases()
    {
        return [];
    }

    /**
     * @return \PHPixie\ORM\Drivers\Driver\PDO\Entity
     * @since 1.0.1
     */
    public function customer()
    {
        return $this->builder->context()->authContext()->user('customer');
    }

    /**
     * @return array
     * @since 1.0
     */
    public function getLinks()
    {
        $links = [];
        foreach($this->links as $href => $attributes) {
            $links[] = '<link href="' . $href . '" ' . $this->parse($attributes) . '/>';
        }

        return $links;
    }

    /**
     * @return array
     * @since 1.0
     */
    public function getMeta()
    {
        $meta = [];
        foreach($this->metaNames as $metaName => $metaValue) {
            $meta[] = '<meta name="' . $metaName . '" content="' . $this->clear($metaValue) . '" />';
        }
        foreach($this->metaProperties as $metaName => $metaValue) {
            $meta[] = '<meta property="' . $metaName . '" content="' . $this->clear($metaValue) . '" />';
        }
        foreach($this->metaHttp as $metaName => $metaValue) {
            $meta[] = '<meta http-equiv="' . $metaName . '" content="' . $this->clear($metaValue) . '" />';
        }

        return $meta;
    }

    /**
     * @return array
     * @since 1.0
     */
    public function getScripts()
    {
        $scripts = [];
        foreach($this->scripts as $href) {
            $scripts[] = '<script type="text/javascript" src="' . $this->clear($href) . '"></script>';
        }

        return $scripts;
    }

    /**
     * @return array
     * @since 1.0
     */
    public function methods()
    {
        return [
            'title'      => 'title',
            'getMeta'    => 'getMeta',
            'getLinks'   => 'getLinks',
            'getScripts' => 'getScripts',
            'url'        => 'url',
            'path'       => 'path',
            'customer'   => 'customer',
            'user'       => 'user',
        ];
    }

    /**
     * @return string
     * @since 1.0
     */
    public function name()
    {
        return 'document';
    }

    /**
     * @param string $processor
     * @param string $action
     * @param string $id
     * @param array  $attributes
     * @param string $resolverPath
     * @return string
     * @since 1.0.1
     */
    public function path($processor, $action = null, $id = null, array $attributes = [], $resolverPath = null)
    {
        if($resolverPath === null) {
            $bundleName   = $this->builder->context()->httpContext()->request()->attributes()->get('bundle');
            $resolverPath = $bundleName . '.processor';
            if($action !== null) {
                $resolverPath = $bundleName . '.action';
                if($id !== null) {
                    $resolverPath = $bundleName . '.id';
                }
            }
        }
        $attributes['processor'] = $processor;
        $attributes['action']    = $action;
        $attributes['id']        = $id;

        return $this->builder->http()->routeTranslator()->generatePath($resolverPath, $attributes);
    }

    /**
     * @param string $href
     * @param array  $attributes
     * @since 1.0
     */
    public function setLink($href, array $attributes = [])
    {
        if($href && $attributes) {
            $this->links[$this->clear($href)] = $attributes;
        }
    }

    /**
     * @param string $value
     * @since 1.0
     */
    public function setMetaDescription($value)
    {
        $this->addMetaName('description', $value);
    }

    /**
     * @param string $value
     * @since 1.0
     */
    public function setMetaKeywords($value)
    {
        $this->addMetaName('keywords', $value);
    }

    /**
     * @param string $value
     * @since 1.0
     */
    public function setMetaOgDescription($value)
    {
        $this->setMetaProperty('og:description', $value);
    }

    /**
     * @param string $value
     * @since 1.0
     */
    public function setMetaOgImage($value)
    {
        $this->setMetaProperty('og:image', $value);
    }

    /**
     * @param string $value
     * @since 1.0
     */
    public function setMetaOgTitle($value)
    {
        $this->setMetaProperty('og:title', $value);
    }

    /**
     * @param string $name
     * @param string $value
     * @since 1.0
     */
    public function setMetaProperty($name, $value)
    {
        if($name && $value) {
            $this->metaProperties[$this->clear($name)] = $value;
        }
    }

    /**
     * @param string $title
     * @return string
     * @since 1.0
     */
    public function title($title = null)
    {
        if($title !== null) {
            $this->title = $this->clear($title);
        }

        return $this->title;
    }

    /**
     * @param string $processor
     * @param string $action
     * @param string $id
     * @param array  $attributes
     * @param string $resolverPath
     * @return \PHPixie\HTTP\Messages\URI\SAPI
     * @since 1.0.1
     */
    public function url($processor = null, $action = null, $id = null, array $attributes = [], $resolverPath = null)
    {
        if($resolverPath === null) {
            $bundleName   = $this->builder->context()->httpContext()->request()->attributes()->get('bundle');
            $resolverPath = $bundleName . '.default';
            if($processor) {
                $resolverPath = $bundleName . '.processor';
                if($action !== null) {
                    $resolverPath = $bundleName . '.action';
                    if($id !== null) {
                        $resolverPath = $bundleName . '.id';
                    }
                }
            }
        }
        $attributes['processor'] = $processor;
        $attributes['action']    = $action;
        $attributes['id']        = $id;

        return $this->builder->http()->routeTranslator()->generateUri($resolverPath, $attributes);
    }

    /**
     * @return \PHPixie\ORM\Drivers\Driver\PDO\Entity
     * @since 1.0.1
     */
    public function user()
    {
        return $this->builder->context()->authContext()->user();
    }

    /**
     * @param string $value
     * @return string
     * @since 1.0
     */
    protected function clear($value)
    {
        $value = preg_replace('/<[^>]+>/', ' ', $value);
        $value = preg_replace('/[\r\n\s]+/', ' ', $value);

        return trim(str_replace('"', '&quot;', $value));
    }

    /**
     * @param array $attributes
     * @return string
     * @since 1.0
     */
    protected function parse(array $attributes = [])
    {
        $result = [];
        foreach($attributes as $key => $value) {
            $result[] = $this->clear($key) . '="' . $this->clear($value) . '"';
        }

        return implode(' ', $result);
    }

}

