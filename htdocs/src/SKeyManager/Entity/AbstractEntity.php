<?php

namespace SKeyManager\Entity;

abstract class AbstractEntity {

   var $id;

   function getAll() {
      return $this->query();
   }

   function getLocation(){
      return sprintf($this->locationPattern, $this->id);
   }

    protected function query() {
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$this->order);
      $row = array();
      $row = mysqli_fetch_assoc($dbresult);
      return $row;
    }

   function updateDb($con, $dbTable, $data, $idString) {
      $sql = '
         UPDATE `'.$dbTable.'`
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
               '.$idString.'
            ';

      $dbresult = queryDb($con, $sql);
      return $dbresult;
   }

   function insertDb($con, $dbTable, $data) {
      $sql = '
         INSERT INTO `'.$dbTable.'`
            ( lastupdate ';
      $keys = '';
      $values = '';
      foreach($data as $key => $value){
         $keys .= ' , '.$key;
         $values .= ' , '.$value;
      }
      $sql .= $keys.' ) VALUES( CURRENT_TIMESTAMP() '.$values.' )';
      $dbresult = queryDb($con, $sql);
      if ($dbresult) {
         $this->id = mysqli_insert_id($con);
      }
      return $dbresult;
   }
}
