<?php

namespace SKeyManager\Repository;

abstract class AbstractRepository {

    function getAll() {
        return $this->query();
    }

    protected function query($where = ' ', $object = null) {
      $result = array();
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$where.$this->order);
      while ($row = mysqli_fetch_assoc($dbresult)){
         $entity = new $object($row['id']);
         $entity->load();
         $result[] = $entity;
      }

      return $result;
    }

   function deleteDb($dbTable, $conditions) {
      // prevent deleteting the whole table
      if(empty($conditions)) {
         return false;
      }

      $con = openDb();
      $sql = '
         DELETE FROM '.$dbTable.'
         WHERE
            ';
         foreach($conditions as $key => $value) {
            $sql .= '
               '.$key.' = '.$value.' AND 
               ';
            }
         $sql .= '
            TRUE
            ';

      $dbresult = queryDb($con, $sql);
      return $dbresult;
   }

   function updateDb($con, $dbTable, $data, $conditions) {
      $sql = '
         UPDATE '.$dbTable.'
         SET
            lastupdate = CURRENT_TIMESTAMP()
            ';
         foreach($data as $key => $value) {
            $sql .= '
               , '.$key.' = '.$value.'
               ';
            }
         $sql .= '
            WHERE
            ';
         foreach($conditions as $key => $value) {
            $sql .= '
               '.$key.' = '.$value.' AND
               ';
            }
         $sql .= '
               TRUE
               ';


      $dbresult = queryDb($con, $sql);
      return $dbresult;
   }

   function insertDb($con, $dbTable, $data) {
      $sql = '
         INSERT INTO '.$dbTable.'
         SET
            lastupdate = CURRENT_TIMESTAMP()
            ';
         foreach($data as $key => $value) {
            $sql .= '
               , '.$key.' = '.$value.'
               ';
            }
      $dbresult = queryDb($con, $sql);
      return $dbresult;
   }

}
