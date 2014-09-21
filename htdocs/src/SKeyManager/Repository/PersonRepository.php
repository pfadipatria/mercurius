<?php

namespace SKeyManager\Repository;

class PersonRepository extends AbstractRepository {

    protected $locationPattern = '/person/%s';

    function __construct() {
        $this->select = '
            SELECT
                id
        ';

        $this->where = '
        ';

        $this->from = '
            FROM doorperson
        ';

        $this->order = '
            ORDER BY name;
        ';
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Person');
    }

    protected function query($where = ' ', $object = 'SKeyManager\Entity\Person') {
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$where.$this->order);
      while ($row = mysqli_fetch_assoc($dbresult)){
         $person = new $object($row['id']);
         $person->load();
         $this->people[] = $person;
      }

      return $this->people;
    }

}
