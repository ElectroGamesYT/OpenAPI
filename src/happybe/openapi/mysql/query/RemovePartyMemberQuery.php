<?php

declare(strict_types=1);

namespace happybe\openapi\mysql\query;

use happybe\openapi\mysql\AsyncQuery;
use mysqli;

/**
 * Class RemovePartyMemberQuery
 * @package happybe\openapi\mysql\query
 */
class RemovePartyMemberQuery extends AsyncQuery {

    /** @var string $owner */
    public $owner;
    /** @var string $member */
    public $member;

    /**
     * RemovePartyMemberQuery constructor.
     * @param string $owner
     * @param string $member
     */
    public function __construct(string $owner, string $member) {
        $this->owner = $owner;
        $this->member = $member;
    }

    /**
     * @param mysqli $mysqli
     */
    public function query(mysqli $mysqli): void {
        $result = $mysqli->query("SELECT * FROM HB_Parties WHERE Owner='{$this->owner}';");
        if($result->num_rows === 0) {
            return;
        }

        $result = $result->fetch_assoc();
        if($result["Members"] == "") {
            return;
        }

        $members = explode(",", $result["Members"]);
        $key = array_search($this->member, $members);
        unset($members[$key]);

        $mysqli->query("UPDATE HB_Parties SET Members='" . implode(",", $members) . "' WHERE Owner='{$this->owner}';");
    }
}