<?php

namespace SKeyManager\Entity;

class Person extends AbstractEntity {

   protected $locationPattern = '/person/%s';
   var $keys;

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
      var_dump($data);
      foreach($data as $name => $value){
         $this->$name = $value;
      }

      //$keys = new \SKeyManager\Repository\KeyRepository;
      // $this->keys = $keys->getByPersonId($this->id);
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

   function setComment($comment) {
      $this->comment = $comment;
      return $this;
   }

   function getLastUpdate() {
      return $this->lastupdate;
   }

   function getKeys() {
      // return $this->keys;
      return null;
   }

   function getNumberOfKeys() {
      return count($this->keys);
   }

   function save() {
      $idString = '';
      $con = openDb();
      if($this->getId()) {
         $idString = ', id = '.mysqli_real_escape_string($con, $this->getId());
      }

      $name = $this->getName() ? '"'.mysqli_real_escape_string($con, $this->getName()).'"' : 'NULL';
      $uid = $this->getUid() ? '"'.mysqli_real_escape_string($con, $this->getUid()).'"' : 'NULL';
      $uidnumber = $this->getUidNumber() ? '"'.mysqli_real_escape_string($con, $this->getUidNumber()).'"' : 'NULL';
      $mdbid = $this->getMdbId() ? '"'.mysqli_real_escape_string($con, $this->getMdbId()).'"' : 'NULL';
      $comment = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      $sql = '
         REPLACE doorperson
         SET name = '.$name.',
            uid = '.$uid.',
            uidnumber = '.$uidnumber.',
            mdbid = '.$mdbid.',
            comment = '.$comment.'
         '.$idString.'
      ';
      $dbresult = queryDb($con, $sql);
      if ($dbresult) {
         $this->id = mysqli_insert_id($con);
      }
      return $dbresult;
   }

   function delete() {
      // @TODO Check if db integrity conditions are fullfilled

      $result = false;

      if($this->getId()) {
         $con = openDb();
         $sql = '
            DELETE FROM doorperson
            WHERE id = '.mysqli_real_escape_string($con, $this->getId()).'
         ';
         $return = queryDb($con, $sql);
      }

      return $return;
   }
}
