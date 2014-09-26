<?php

namespace SKeyManager\Entity;

class History extends AbstractEntity {

    protected $locationPattern = '/history/%s';

    function __construct($id = null) {
        $this->select = '
            SELECT
               history.id AS id,
               history.keyid,
               doorkey.code AS keycode,
               history.lockid,
               doorlock.sc AS lockcode,
               personid,
               doorperson.name AS personname,
               history.comment AS comment,
               history.userid,
               user.name AS username,
               history.lastupdate AS lastupdate
        ';

        $this->from = '
            FROM history
            LEFT JOIN doorkey ON (history.keyid = doorkey.id)
            LEFT JOIN doorlock ON (history.lockid = doorlock.id)
            LEFT JOIN doorperson ON (history.personid = doorperson.id)
            LEFT JOIN doorperson AS user ON (history.userid = user.id)
        ';

      $this->where = '
         WHERE history.id = '.$id.'
      ';

        $this->order = '
            ORDER BY history.lastupdate DESC
        ';
    }

   // Needed to provide a Name of a 'empty' history entry ?
   function __get() {
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

   function getLockId(){
      return $this->lockid;
   }

   function setLockId($lockId) {
      $this->lockid = $lockId;
      return $this;
   }

   function getPersonId(){
      return $this->personid;
   }

   function setPersonId($personId) {
      $this->personid = $personId;
      return $this;
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
