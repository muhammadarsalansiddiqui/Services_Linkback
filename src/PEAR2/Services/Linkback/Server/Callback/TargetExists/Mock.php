<?php
/**
 * This file is part of the PEAR2\Services\Linkback package.
 *
 * PHP version 5
 *
 * @category Services
 * @package  PEAR2\Services\Linkback
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link     http://pear2.php.net/package/Services_Linkback
 */
namespace PEAR2\Services\Linkback\Server\Callback\TargetExists;

/**
 * Pingback server callback interface: Verify that the target URI exists
 * in our system.
 *
 * @category Services
 * @package  PEAR2\Services\Linkback
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link     http://pear2.php.net/package/Services_Linkback
 */
class Mock implements \PEAR2\Services\Linkback\Server\Callback\ITarget
{
    /**
     * If the target should exist or not
     *
     * @var boolean
     */
    protected $targetExists = true;

    /**
     * Exception to throw when verifyTargetExists() is called
     *
     * @var \Exception
     */
    protected $exception;

    /**
     * Set the mock response to the "does the target exist" question.
     *
     * @param boolean $targetExists Return value for the verifyTargetExists() method
     *
     * @return void
     */
    public function setTargetExists($targetExists)
    {
        $this->targetExists = $targetExists;
    }

    /**
     * Set an exception that gets thrown when the verifyTargetExists() method
     * gets called.
     *
     * @param \Exception $ex Exception or NULL
     *
     * @return void
     */
    public function setException(\Exception $ex = null)
    {
        $this->exception = $ex;
    }

    /**
     * Verifies that the given target URI exists in our system.
     *
     * @param string $target Target URI that got linked to
     *
     * @return boolean True if the target URI exists, false if not
     *
     * @throws Exception When something fatally fails
     */
    public function verifyTargetExists($target)
    {
        if ($this->exception !== null) {
            throw $this->exception;
        }
        return $this->targetExists;
    }
}


?>
