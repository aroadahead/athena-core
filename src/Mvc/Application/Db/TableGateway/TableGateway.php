<?php
/**
 * @author jrk <me at aroadahead.com>
 * @copyright 2021 A Road Ahead, LLC
 * @license Apache 2.0
 */
declare(strict_types=1);

/**
 * @package \Core\Application\Db\TableGateway
 */

namespace AthenaCore\Mvc\Application\Db\TableGateway;

/**
 * Import Statements
 */

use Application\Entity\AbstractEntity;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;
use Laminas\Db\TableGateway\TableGateway as BridgeTableGateway;
use Laminas\Filter\Word\CamelCaseToUnderscore;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\ObjectPropertyHydrator;

/**
 * Class TableGateway
 *
 * @package \Core\Application\Db\TableGateway
 * @extends BridgeTableGateway
 */
class TableGateway extends BridgeTableGateway
{
    /**
     * SQL Percent
     *
     * @var string
     */
    public const SQL_PERCENT = "%";

    /**
     * Model Type
     *
     * @var string
     */
    public const TYPE_MODEL = "Model";

    /**
     * Entity Type
     *
     * @var string
     */
    public const TYPE_ENTITY = "Entity";

    /**
     * Db DataSet
     *
     * @var DbDataSet
     */
    protected DbDataSet $dataSet;

    /**
     * Master Db Adapter
     *
     * @var Adapter
     */
    protected Adapter $masterAdapter;

    /**
     * Slave Db Adapter
     *
     * @var Adapter
     */
    protected Adapter $slaveAdapter;

    /**
     * Master Sql
     *
     * @var Sql
     */
    protected Sql $masterSql;

    /**
     * Slave Sql
     *
     * @var Sql
     */
    protected Sql $slaveSql;

    /**
     * Object Prototype
     *
     * @var AbstractEntity|TableGateway
     */
    protected mixed $objectPrototype;

    /**
     * Class Methods Hydrator
     *
     * @var ClassMethodsHydrator
     */
    protected ClassMethodsHydrator $hydrator;

    /**
     * Select
     *
     * @var Select
     */
    protected Select $select;

    /**
     * Original Classname.
     *
     * @var string
     */
    protected string $originalClassName;

    /**
     * Class.
     *
     * @var string|null
     */
    protected ?string $clazz = null;

    /**
     * Use Model for Prototype
     *
     * @var bool
     */
    protected bool $useModelForPrototype;

    /**
     * Join Count
     *
     * @var int
     */
    protected int $joinCnt = 0;

    /**
     * @param bool $useModelForPrototype
     * @param string|null $table
     */
    public function __construct(bool $useModelForPrototype = false, string $table = null)
    {
        $this -> dataSet = new DbDataSet();
        $clazz = get_class($this);
        $clazz = substr($clazz, strrpos($clazz, '\\') + 1);
        $filter = new CamelCaseToUnderscore();
        $this -> originalClassName = $clazz;
        if (is_null($table)) {
            $table = strtolower($filter -> filter($clazz)) . 's';
        }

        $this -> masterAdapter = GlobalAdapterFeature ::getStaticAdapter();
        $this -> slaveAdapter = GlobalAdapterFeature ::getStaticAdapter();
        $this -> sql = new Sql($this -> masterAdapter, $table);
        $this -> masterSql = $this -> sql;
        $this -> slaveSql = new Sql($this -> slaveAdapter, $table);
        $this -> featureSet = new FeatureSet();
        $clazz = get_class($this);
        $this -> clazz = $clazz;
        if (!$useModelForPrototype) {
            $clazz = str_ireplace(static::TYPE_MODEL, static::TYPE_ENTITY, $clazz);
            $this -> clazz = $clazz;
            $this -> objectPrototype = new $clazz();
        } else {
            $this -> objectPrototype = new $clazz(false, $table);
        }
        $this -> useModelForPrototype = $useModelForPrototype;
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype -> setHydrator(new ObjectPropertyHydrator());
        $resultSetPrototype -> setObjectPrototype($this -> objectPrototype);
        $this -> resultSetPrototype = $resultSetPrototype;
        $this -> hydrator = new ClassMethodsHydrator();
        $this -> select = $this -> masterSql -> select();
        parent ::__construct($table, $this -> masterAdapter, $this -> featureSet,
            $this -> resultSetPrototype, $this -> sql);
    }

    /**
     * Set current select.
     *
     * @param Select $select
     */
    public function setCurrentSelect(Select $select): void
    {
        $this -> select = $select;
    }

    /**
     * Return current select.
     *
     * @return Select
     */
    public function getCurrentSelect(): Select
    {
        return $this -> select;
    }

    /**
     * Return Dataset.
     *
     * @return DbDataSet
     */
    public function getDataSet(): DbDataSet
    {
        return $this -> dataSet;
    }

    /**
     * Format parameter name.
     *
     * @param string $name
     * @return string
     */
    protected function formatParameterName(string $name): string
    {
        return $this -> adapter -> driver -> formatParameterName($name);
    }

    /**
     * Quote Identifier.
     *
     * @param string $name
     * @return string
     */
    protected function quoteIdentifier(string $name): string
    {
        return $this -> adapter -> platform -> quoteIdentifier($name);
    }

    /**
     * Return table total rows.
     *
     * @param string|null $table
     * @return int
     */
    public static function tableTotalRows(string $table = null): int
    {
        $clazz = new static(false, $table);
        $sql = "select count(1) as numberOfRecords from %s";
        $sql = sprintf($sql, $clazz -> getAdapter() -> platform -> quoteIdentifierChain([$clazz -> getTable()]));
        $stmt = $clazz -> adapter -> getDriver() -> createStatement($sql);
        $stmt -> prepare();
        return (int)($stmt -> execute()) -> current()['numberOfRecords'];
    }

    /**
     * Fetch all records.
     *
     * @return HydratingResultSet
     */
    public function fetchAll(): HydratingResultSet
    {
        $stmt = $this -> masterSql -> prepareStatementForSqlObject($this -> getCurrentSelect());
        $this -> resultSetPrototype -> initialize($stmt -> execute());
        return $this -> resultSetPrototype;
    }

    /**
     * Fetch one record.
     *
     * @return object|null
     */
    public function fetchOne(): ?object
    {
        $stmt = $this -> masterSql -> prepareStatementForSqlObject($this -> getCurrentSelect());
        $this -> resultSetPrototype -> initialize($stmt -> execute());
        return $this -> resultSetPrototype -> current();
    }

    /**
     * Join outer
     *
     * @param string $objectClass
     * @param array $columns
     * @param int $joinId
     * @param string|null $joinCol
     */
    public function joinObjectOuter(string $objectClass, array $columns, int $joinId = 0, string $joinCol = null): void
    {
        $this -> join($objectClass, $joinId, $columns, Select::JOIN_OUTER, $joinCol);
    }

    /**
     * Join inner
     *
     * @param string $objectClass
     * @param array $columns
     * @param int $joinId
     * @param string|null $joinCol
     */
    public function joinObjectInner(string $objectClass, array $columns, int $joinId = 0, string $joinCol = null): void
    {
        $this -> join($objectClass, $joinId, $columns, Select::JOIN_INNER, $joinCol);
    }

    /**
     * Join right
     *
     * @param string $objectClass
     * @param array $columns
     * @param int $joinId
     * @param string|null $joinCol
     */
    public function joinObjectRight(string $objectClass, array $columns, int $joinId = 0, string $joinCol = null): void
    {
        $this -> join($objectClass, $joinId, $columns, Select::JOIN_RIGHT, $joinCol);
    }

    /**
     * Join left
     *
     * @param string $objectClass
     * @param array $columns
     * @param int $joinId
     * @param string|null $joinCol
     * @param string|null $srcJoin
     */
    public function joinObjectLeft(string $objectClass, array $columns, int $joinId = 0, string $joinCol = null, string $srcJoin = null): void
    {
        $this -> join($objectClass, $joinId, $columns, Select::JOIN_LEFT, $joinCol, $srcJoin);
    }

    /**
     * Save data.
     *
     * @param bool $reloadData
     * @param int $status
     */
    public function save(bool $reloadData = true, int $status = 1)
    {
        if ($this -> dataSet -> hasId()) {
            $this -> dataSet -> set('updated', nowSql());
            $this -> update(
                $this -> dataSet -> all(['id']),
                array('id' => $this -> dataSet -> getId())
            );
        } else {
            $this -> dataSet -> add([
                'parentid' => 0,
                'flags' => 0,
                'status' => $status,
                'created' => nowSql(),
                'updated' => nowSql(),
                'hash' => new Expression("UUID()")
            ]);
            $this -> insert($this -> dataSet -> all());
            $this -> dataSet -> offsetSet('id', $this -> getLastInsertValue());
        }
        if ($reloadData) {
            $clazz = $this -> clazz;
            $clazz = str_ireplace(static::TYPE_ENTITY, static::TYPE_MODEL, $clazz);
            $instance = new $clazz(false, $this -> getTable());
            if (is_int($this -> dataSet -> getId()) && ($this -> dataSet -> getId() > 0)) {
                $data = $instance -> select(['id' => $this -> dataSet -> getId()]) -> current();
            } else {
                $data = $instance -> select(['id' => $this -> getLastInsertValue()]) -> current();
            }
            $this -> dataSet -> add($data -> toArray());
        }
    }

    /**
     * Remove Data.
     *
     * @param bool $force
     */
    public function remove(bool $force = false)
    {
        if ($force) {
            $this -> delete(['id' => $this -> dataSet -> getId()]);
        } else {
            $this -> dataSet -> setStatus(0);
            $this -> save();
        }
    }

    /**
     * Magic get.
     *
     * @param string $property
     * @return array|int|string
     */
    public function __get($property): array|int|string
    {
        if ($this -> dataSet -> has($property)) {
            return $this -> dataSet -> get($property);
        }
        throw new InvalidArgumentException('Invalid magic property access in ' . __CLASS__ . '::__get()');
    }

    /**
     * Magic set.
     *
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public function __set($property, $value): void
    {
        $this -> dataSet -> offsetSet($property, $value);
    }

    /**
     * Magic call.
     *
     * @param $method
     * @param $args
     * @return bool|mixed|void
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this -> dataSet -> underscore(substr($method, 3));
                return $this -> dataSet -> offsetGet($key);
            case 'set':
                $key = $this -> dataSet -> underscore(substr($method, 3));
                $value = $args[0] ?? null;
                $this -> dataSet -> offsetSet($key, $value);
                break;
            case 'uns':
                $key = $this -> dataSet -> underscore(substr($method, 5));
                $this -> dataSet -> offsetUnset($key);
                break;
            case 'rem':
                $key = $this -> dataSet -> underscore(substr($method, 6));
                $this -> dataSet -> offsetUnset($key);
                break;
            case 'has':
                $key = $this -> dataSet -> underscore(substr($method, 3));
                return $this -> dataSet -> offsetExists($key);
        }
    }

    /**
     * Join data
     *
     * @param string $objectClass
     * @param int $joinId
     * @param array $columns
     * @param string $joinType
     * @param string|null $joinCol
     * @param string|null $srcJoin
     */
    private function join(string $objectClass, int $joinId, array $columns, string $joinType, string $joinCol = null, string $srcJoin = null)
    {
        /* @var $obj TableGateway */
        $obj = new $objectClass();
        $joinAlias = "j" . $this -> joinCnt;
        $tableName = $obj -> getTable();
        if (is_null($joinCol)) {
            $joinCol = strtolower($this -> originalClassName) . "id";
        }
        if (is_null($srcJoin)) {
            $joinOn = $this -> getTable() . ".id=$joinAlias.$joinCol";
        } else {
            $joinOn = $this -> getTable() . ".$srcJoin=$joinAlias.$joinCol";
        }
        if (empty($columns)) {
            $columns = Select::SQL_STAR;
        }
        $select = $this -> getCurrentSelect() -> join([$joinAlias => $tableName], $joinOn, $columns, $joinType);
        if ($joinId > 0) {
            $joinOnId = $joinAlias . ".id";
            $select -> where([$joinOnId => $joinId]);
        }
        $this -> setCurrentSelect($select);
        $this -> joinCnt++;
    }
}