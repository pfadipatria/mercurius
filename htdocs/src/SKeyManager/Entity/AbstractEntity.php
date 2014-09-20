<?php

namespace SKeyManager\Entity;

abstract class AbstractEntity {

   var $id;

   function getAll() {
      return $this->query();
   }

    protected function query() {
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$this->order);
      $row = array();
      $locations = array();
      $row = mysqli_fetch_assoc($dbresult);
      return $row;
    }

}
