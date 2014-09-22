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

   // Needed to provide a Name of a 'empty' person ?
   function __get($arg = null) {
      return null;
   }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }

      // We can't load the Keys here, as it would loop (persons are 'owned' by a key anyway, not vice versa)
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
      // return null;

      $keys = new \SKeyManager\Repository\KeyRepository;
      return $keys->getByPersonId($this->id);
   }

   function getNumberOfKeys() {
      return count($this->getKeys());
   }

   function save() {
      $dbTable = 'doorperson';
      $con = openDb();

      $data = array();
      $data['name'] = $this->getName() ? '"'.mysqli_real_escape_string($con, $this->getName()).'"' : 'NULL';
      $data['uid'] = $this->getUid() ? '"'.mysqli_real_escape_string($con, $this->getUid()).'"' : 'NULL';
      $data['uidnumber'] = $this->getUidNumber() ? '"'.mysqli_real_escape_string($con, $this->getUidNumber()).'"' : 'NULL';
      $data['mdbid'] = $this->getMdbId() ? '"'.mysqli_real_escape_string($con, $this->getMdbId()).'"' : 'NULL';
      $data['comment'] = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      if($this->getId()) {
         $idString = ' id = '.mysqli_real_escape_string($con, $this->getId());
         return $this->updateDb($con, $dbTable, $data, $idString);
      } else {
         return $this->insertDb($con, $dbTable, $data);
      }
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
