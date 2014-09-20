<?php

namespace SKeyManager\Entity;

abstract class AbstractEntity {

   var $id;

   function getAll() {
      return $this->query();
   }

    protected function query($where = ' true ') {
      $where .= ' AND id = '.$this->id;
      $con = openDb();
      error_log($this->select.$this->from.$where.$this->order);
      $dbresult = queryDb($con, $this->select.$this->from.$where.$this->order);
      $row = array();
      $locations = array();
      $row = mysqli_fetch_assoc($dbresult);
      return $row;
    }

}
