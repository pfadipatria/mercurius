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

      $keys = new \SKeyManager\Repository\KeyRepository;
      $this->keys = $keys->getByPersonId($this->id);
      // $this->keys = $keys->getAll();
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

   function getUid() {
      return $this->uid;
   }

   function setUid($uid) {
      $this->uid = $uid;
      return $this;
   }

   function getUidNumber() {
      return $this->uidnumber;
   }

   function setUidNumber($uidnumber) {
      $this->uidnumber = $uidnumber;
      return $this;
   }

   function getMdbId() {
      return $this->mdbid;
   }

   function setMdbId($mdbid) {
      $this->mdbid = $mdbid;
      return $this;
   }

   function getComment() {
      return $this->comment;
   }

   function getLastUpdate() {
      return $this->lastupdate;
   }

   function getKeys() {
      return $this->keys;
   }

   function save() {
      $idString = '';
      $con = openDb();
      if($this->getId()) {
         $idString = ', id = '.mysqli_real_escape_string($con, $this->getId());
      }
      $sql = '
         REPLACE doorperson
         SET name = "'.mysqli_real_escape_string($con, $this->getName()).'"
         '.$idString.'
      ';
      $dbresult = queryDb($con, $sql);
      if ($dbresult) {
         $this->id = mysqli_insert_id($con);
      }
      return $dbresult;
   }

}
