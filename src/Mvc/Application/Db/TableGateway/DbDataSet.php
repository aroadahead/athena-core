<?php
/**
 * @author jrk
 * @copyright 2021 A Road Ahead, LLC
 * @license Apache 2.0
 */
declare(strict_types=1);

/**
 * @package \AthenaCore\Mvc\Application\Db\TableGateway
 */

namespace AthenaCore\Mvc\Application\Db\TableGateway;

/**
 * Import Statements
 */

use Poseidon\Data\DataObject;
use JetBrains\PhpStorm\Pure;

/**
 * Class DbDataSet
 *
 * @package \Core\Application\Db\TableGateway
 * @extends DataObject
 */
class DbDataSet extends DataObject
{
    /**
     * Return Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this -> offsetGet('id');
    }

    /**
     * Return Parent Id.
     *
     * @return int
     */
    public function getParentid(): int
    {
        return (int)$this -> offsetGet('parentid');
    }

    /**
     * Return Hash.
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this -> offsetGet('hash');
    }

    /**
     * Return Date Created.
     *
     * @return string
     */
    public function getCreated(): string
    {
        return $this -> offsetGet('created');
    }

    /**
     * Return Date Updated.
     *
     * @return string
     */
    public function getUpdated(): string
    {
        return $this -> offsetGet('updated');
    }

    /**
     * Has Id?
     *
     * @return bool
     */
    #[Pure] public function hasId(): bool
    {
        return $this -> offsetExists('id');
    }

    /**
     * Set Status.
     *
     * @param int $status
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this -> offsetSet('status', $status);
    }
}