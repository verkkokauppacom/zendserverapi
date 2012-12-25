<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link           http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright      Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license        http://framework.zend.com/license/new-bsd New BSD License
 * @package        Zend_Service
 */
namespace ZendService\ZendServerAPI\DataTypes;

/**
 * Filter collection model implementation.
 *
 * @license        http://framework.zend.com/license/new-bsd New BSD License
 * @link           http://github.com/zendframework/zf2 for the canonical source repository
 * @author         Ingo Walz <ingo.walz@googlemail.com>
 * @category       Zend
 * @package        Zend_Service
 * @subpackage     ZendServerAPI
 */
class Filters extends DataType implements \Countable, \IteratorAggregate
{
    /**
     * Internal filter storage
     * @var array
     */
    protected $filters = array();

    /**
     * Add filter to container
     *
     * @param  \ZendService\ZendServerAPI\DataTypes\Filter $filter
     * @return void
     */
    public function addFilter(\ZendService\ZendServerAPI\DataTypes\Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Get the filter array
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Implementation for traversable
     *
     * @see IteratorAggregate::getIterator()
     * @return \ArrayIterator
     */
    public function getIterator ()
    {
        return new \ArrayIterator($this->filters);
    }

    /**
     * Implementation for countable
     *
     * @see Countable::count()
     * @return int
     */
    public function count ()
    {
        return count($this->getIterator());
    }

}
