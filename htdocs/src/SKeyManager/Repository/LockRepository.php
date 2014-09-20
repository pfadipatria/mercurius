<?php

namespace SKeyManager\Repository;

class LockRepository extends AbstractRepository {

    protected $locationPattern = '/locks/show/%s';

    function __construct() {
        $this->select = '
            SELECT
               doorlock.id AS id,
               number,
               doorlock.name AS lockname,
               sc,
               comment,
               doorplace.name AS heim,
               doorlockstatus.name AS statusname
        ';

        $this->from = '
               FROM doorlock
               LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
               LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
        ';

        $this->order = '
            ORDER BY sc
        ';
    }

   function getAllowedByKeyId($id) {
      $return = '';
      $this->select = 'SELECT
         doorkey_opens_lock.lock AS lockid,
         doorlock.sc AS locksc,
         doorplace.name AS heim,
         doorlock.name AS lockname
         ';
      $this->from = '
         FROM doorkey
         LEFT JOIN doorkey_opens_lock ON (doorkey.id = doorkey_opens_lock.key )
         LEFT JOIN doorlock ON (doorkey_opens_lock.lock = doorlock.id )
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         ';
      $this->where = '
         WHERE doorkey_opens_lock.key = '.$id.'
      ';
        $this->order = '
            ORDER BY doorlock.sc
        ';
      error_log($this->select.$this->from.$where.$this->order);

        $con = openDb();
        $dbresult = queryDb($con, $this->select.$this->from.$this->where.$where.$this->order);
        $rows = array();
        $locations = array();
        while ($row = mysqli_fetch_assoc($dbresult)){
            $locations[] = sprintf($this->locationPattern, $row['lockid']);
            $rows[] = $row;
        }
        return array($rows, $locations);

      return $rows;
    }
}

