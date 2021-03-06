<?php

declare(strict_types=1);

namespace happybe\openapi\mysql\query;

use happybe\openapi\mysql\AsyncQuery;
use happybe\openapi\mysql\DatabaseData;
use mysqli;
use pocketmine\Server;

/**
 * Class FetchCacheQuery
 * @package happybe\openapi\mysql\query
 */
class FetchCacheQuery extends AsyncQuery {

    /** @var string $player */
    public $player;
    /** @var string $tables */
    public $tables;

    /** @var string|array $cache */
    public $cache;

    /**
     * FetchCacheQuery constructor.
     * @param string $player
     * @param array $tables
     */
    public function __construct(string $player, array $tables) {
        $this->player = $player;
        $this->tables = serialize($tables);
    }

    /**
     * @param mysqli $mysqli
     */
    public function query(mysqli $mysqli): void {
        $cache = [];

        foreach (unserialize($this->tables) as $table) {
            $result = $mysqli->query("SELECT * FROM " . DatabaseData::TABLE_PREFIX . "_" . $table . " WHERE Name='{$this->player}';");
            $cache[$table] = $result->fetch_assoc();
        }

        $this->cache = serialize($cache);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server) {
        $this->cache = unserialize($this->cache);
        parent::onCompletion($server); // TODO: Change the autogenerated stub
    }
}