<?php

namespace App\Source\Composite;

use App\Source\Composite\Interfaces\IMenuComposite;

/**
 * Class AMenu
 * @package App\Source\Composite
 */
abstract class AMenu implements IMenuComposite
{
    /**
     * @var int
     */
    protected static $last_id=0;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $menu = [];

    /**
     * Reserved keys
     *
     * @var array
     */
    protected $reserved = ['url', 'link_attr', 'meta_attr'];

    /**
     * Item's meta data
     *
     * @var array
     */
    protected $meta = [];

    /**
     * Item's attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Item's hyperlink
     *
     * @var Link
     */
    public    $link;

    /**
     * @param $name
     * @param null $options
     */
    public function __construct($name, $options = null)
    {
        $url  = $this->getUrl($options);

        $this->name       = strtolower(preg_replace('/[^\w\d\-\_]/s', "", $name));
        $this->attributes = ( is_array($options) ) ? $this->extractAttr($options) : array();

        if( is_array($options) )
            $linkAttr = $options['link_attr'];

        if( is_array($options) )
            $this->meta = $options['meta_attr'];

        // Create an object of type Link
        $this->link       = new MenuLink($name, $url, $linkAttr);

        $this->id = ++self::$last_id;
    }

    /**
     * @param IMenuComposite $menuItem
     * @return mixed
     */
    abstract public function add(IMenuComposite $menuItem);

    /**
     * @param $id
     * @return mixed
     */
    abstract public function remove($id);

    /**
     * @return mixed
     */
    abstract public function getChild();

    /**
     * @param int $id
     * @return mixed
     */
    abstract public function getParent($id = 0);

    /**
     * @param $options
     * @return mixed
     */
    abstract public function getUrl($options);

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLastId()
    {
        return self::$last_id;
    }
}