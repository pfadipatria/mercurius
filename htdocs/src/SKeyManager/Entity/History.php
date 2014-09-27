<?php

namespace SKeyManager\Entity;

class History extends AbstractEntity {

    protected $locationPattern = '/history/%s';

    function __construct($id = null) {
        $this->select = '
            SELECT
               history.id AS id,
               history.keyid,
               key.code AS keycode,
               history.lockid,
               lock.sc AS lockcode,
               personid,
               person.name AS personname,
               history.comment AS comment,
               history.userid,
               user.name AS username,
               history.lastupdate AS lastupdate
        ';

        $this->from = '
            FROM `history`
            LEFT JOIN `key` ON (history.keyid = key.id)
            LEFT JOIN `lock` ON (history.lockid = lock.id)
            LEFT JOIN `person` ON (history.personid = person.id)
            LEFT JOIN `person` AS user ON (history.userid = user.id)
        ';

      $this->where = '
         WHERE history.id = '.$id.'
      ';

        $this->order = '
            ORDER BY history.lastupdate DESC
        ';
    }

   // Needed to provide a Name of a 'empty' history entry ?
   function __get($arg) {
      return null;
   }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }

   }

   function getId(){
      return $this->id;
   }

   function getKeyId(){
      return $this->keyid;
   }

   function setKeyId($keyId) {
      $this->keyid = $keyId;
      return $this;
   }

   function getKeyCode(){
      return $this->keycode;
   }

   function getLockId(){
      return $this->lockid;
   }

   function setLockId($lockId) {
      $this->lockid = $lockId;
      return $this;
   }

   function getLockCode(){
      return $this->lockcode;
   }

   function getPersonId(){
      return $this->personid;
   }

   function setPersonId($personId) {
      $this->personid = $personId;
      return $this;
   }

   function getPersonName(){
      return $this->personname;
   }

   function getComment(){
      return $this->comment;
   }

   function getAuthorId(){
      return $this->userid;
   }

   function setAuthorId($userId) {
      $this->userid = $userId;
      return $this;
   }

   function getAuthorName(){
      return $this->username;
   }

   function setAuthorName($username) {
      $this->username = $username;
      return $this;
   }

   function setComment($comment) {
      $this->comment = $comment;
      return $this;
   }

   function getDate(){
      return $this->lastupdate;
   }

   function save() {
      $dbTable = 'history';
      $con = openDb();

      $data = array();
      $data['keyid'] = $this->getKeyId() ? '"'.mysqli_real_escape_string($con, $this->getKeyId()).'"' : 'NULL';
      $data['lockid'] = $this->getLockId() ? '"'.mysqli_real_escape_string($con, $this->getLockId()).'"' : 'NULL';
      $data['personid'] = $this->getPersonId() ? '"'.mysqli_real_escape_string($con, $this->getPersonId()).'"' : 'NULL';
      $data['comment'] = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';
      $data['userid'] = $this->getAuthorId() ? '"'.mysqli_real_escape_string($con, $this->getAuthorId()).'"' : 'NULL';

      return $this->insertDb($con, $dbTable, $data);
   }

}
