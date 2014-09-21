<?php

namespace SKeyManager\Repository;

class KeyRepository extends AbstractRepository {

    protected $locationPattern = '/key/%s';

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

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }
   }

    function getByPersonId($id) {
        return $this->query('WHERE doorperson.id = '.$id, 'SKeyManager\Entity\Key');
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
         $this->keys[$row['id']] = new SKeyManager\Entity\Key($row['id']);
      }

      return $this->keys;
    }

}
