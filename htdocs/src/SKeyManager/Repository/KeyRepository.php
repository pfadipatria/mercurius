<?php

namespace SKeyManager\Repository;

class KeyRepository extends AbstractRepository {

    protected $locationPattern = '/key/%s';
    var $keys = array();

    function __construct() {
        $this->select = '
            SELECT
               id
        ';

        $this->from = '
            FROM doorkey
        ';

        $this->where = '
        ';

        $this->order = '
            ORDER BY code
        ';
    }

    function getByPersonId($id) {
        return $this->query('WHERE owner = '.$id, 'SKeyManager\Entity\Key');
    }

    function getByAllowedForLock($id) {
        return $this->query('WHERE doorperson.id = '.$id);
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Key');
    }

    protected function query($where = ' ', $object = 'SKeyManager\Entity\Key') {
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$where.$this->order);
      while ($row = mysqli_fetch_assoc($dbresult)){
         $key = new $object($row['id']);
         $key->load();
         $this->keys[] = $key;
      }

      return $this->keys;
    }

}
