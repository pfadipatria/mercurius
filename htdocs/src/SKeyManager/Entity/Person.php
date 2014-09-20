<?php

namespace SKeyManager\Entity;

class Person extends AbstractEntity {

   protected $locationPattern = '/person/%s';

   function __construct($id = null) {
      $this->select = '
         SELECT
             id,
             name,
             uid,
             uidnumber,
             mdbid,
             comment,
             lastupdate
      ';

      $this->from = '
         FROM doorperson
      ';
      $this->where = '
         WHERE doorperson.id = '.$id.'
      ';
      $this->order = '
         ORDER BY name;
      ';
   }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }
   }

   function getId() {
      return $this->id;
   }

   function getName() {
      return $this->name;
   }

   function setName($name) {
      $this->name = $name;
      return $this;
   }

   function save() {
      $idString = '';
      if($this->getId()) {
         $idString = ', id = '.mysqli_real_escape_string($con, $this->getId());
      }
      $con = openDb();
      $sql = '
         REPLACE doorperson
         SET name = "'.mysqli_real_escape_string($con, $this->getName()).'"
         '.$idString.'
      ';
      return queryDb($con, $sql);
   }

}
