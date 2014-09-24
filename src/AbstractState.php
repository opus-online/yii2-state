<?php
/**
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @date 18.08.2014
 */

namespace opus\state;

use yii\base\Component;
use yii\base\InvalidParamException;
use yii\caching\Cache;
use yii\caching\DummyCache;

/**
 * Common state functionality, supports many states
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\state
 */
abstract class AbstractState extends Component
{
    /**
     * Sets the cache duration
     *
     * @var integer
     */
    public $cacheId = 'cache';
    /**
     * Sets the cache duration. 0 Means caching is disabled
     *
     * @var integer
     */
    public $cachingDuration = 0;
    /**
     * @var array
     */
    public $defaults = array();
    /**
     * @var array
     */
    protected $map;

    /**
     * @param string $name Name of the state
     * @param mixed $value Scalar value
     * @return boolean
     */
    abstract protected function setState($name, $value);

    /**
     * @param string $name
     * @return boolean
     */
    abstract protected function deleteState($name);

    /**
     * @return mixed
     */
    abstract protected function loadStates();

    /**
     * @param string $name
     * @param string $value
     * @return bool
     */
    public function set($name, $value)
    {
        if (!is_scalar($name)) {
            throw new InvalidParamException('Only scalar names are allowed');
        }
        $this->setState($name, $value);
        $this->loadStates();

        return true;
    }

    /**
     * Deletes a state if it exists
     *
     * @param string $name
     * @return bool
     */
    public function delete($name)
    {
        $success = $this->deleteState($name);
        return $success;
    }

    /**
     * Returns an item from the states array
     *
     * @param string $name
     * @param mixed $default
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        if (isset($this->map[$name])) {
            return $this->map[$name];
        }

        if ($default === null && isset($this->defaults[$name])) {
            $default = $this->defaults[$name];
        }

        return $default;
    }

    /**
     * Initializes the component
     *
     * @return void
     */
    public function init()
    {
        $cachedValues = $this->getCache()->get($this->getComponentId());
        if ((false !== $cachedValues)) {
            $this->map = $cachedValues;
        } else {
            $this->loadStates();
        }

        parent::init();
    }

    /**
     * Returns the cache handler for this component
     *
     * @throws \yii\base\InvalidConfigException
     * @return Cache
     */
    protected function getCache()
    {
        if (($handler = \Yii::$app->get($this->cacheId))) {
            return $handler;
        }

        return new DummyCache();
    }

    /**
     * @return string
     */
    protected function getComponentId()
    {
        return __NAMESPACE__;
    }

    protected function updateCache()
    {
        if ($this->cachingDuration > 0) {
            $this->getCache()->set(
                $this->getComponentId(),
                $this->map,
                $this->cachingDuration
            );
        }
    }
}
