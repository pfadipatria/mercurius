<?php

namespace SKeyManager\Entity;

class Key extends AbstractEntity {

    protected $locationPattern = '/key/%s';
   var $holder;
   var $allowedLocks;
   var $denyingLocks;

    function __construct($id = null) {
        $this->select = '
            SELECT
               doorkey.id,
               elnumber,
               code,
               type,
               doorkeycolor.name AS colorname,
               doorkeycolor.id AS colorid,
               doorkeystatus.name AS statusname,
               doorkeystatus.id AS statusid,
               doorkeymech.bezeichung AS description,
               holder AS holderid,
               doorkey.comment AS comment,
               communication,
               doorkey.lastupdate AS lastupdate
        ';

        $this->from = '
            FROM doorkey
            LEFT JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id)
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
            LEFT JOIN doorkeymech ON (doorkey.mech = doorkeymech.id)
        ';

      $this->where = '
         WHERE doorkey.id = '.$id.'
      ';

        $this->order = '
            ORDER BY doorkey.code
        ';
    }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }

      $holder = null;
      if(!empty($this->holderid)) {
         $holder = new \SKeyManager\Entity\Person($this->holderid);
         $holder->load();
      } else {
         $holder = new \SKeyManager\Entity\Person();
      }
      $this->holder = $holder;
   }

   function getId(){
      return $this->id;
   }

   function getCode(){
      return $this->code;
   }

   function setCode($code) {
      $this->code = $code;
      return $this;
   }

   function getElNumber(){
      return $this->elnumber;
   }

   function setElNumber($elnumber) {
      $this->elnumber = $elnumber;
      return $this;
   }

   function getType(){
      return $this->type;
   }

   function setType($type) {
      $this->type = $type;
      return $this;
   }

   function getColorId(){
      return $this->colorid;
   }

   function setColorId($colorid) {
      $this->colorid = $colorid;
      return $this;
   }

   function getStatus() {
      return $this->getStatusName();
   }

   function getStatusName(){
      return $this->statusname;
   }

   function getStatusId(){
      return $this->statusid;
   }

   function setStatusId($statusid) {
      $this->statusid = $statusid;
      return $this;
   }

   function getHolder() {
      return $this->holder;
   }

   function getHolderId() {
      return $this->holderid;
   }

   function setHolderId($holderid) {
      $this->holderid = $holderid;
      return $this;
   }

   function getHolderName(){
      return $this->getHolder()->getName();
   }

   function getOwner() {
      return $this->getHolder();
   }

   function getOwnerName(){
      return $this->getHolderName();
   }

   function getComment(){
      return $this->comment;
   }

   function setComment($comment) {
      $this->comment = $comment;
      return $this;
   }

   function getLastUpdate(){
      return $this->lastupdate;
   }

   function getName() {
      $name = 'MC '.$this->getCode();
      $name .= $this->getHolderName() ? ' - '.$this->getHolderName() : '';
      return $name;
   }

   function save() {
      $idString = '';
      $con = openDb();
      if($this->getId()) {
         $idString = ', id = '.mysqli_real_escape_string($con, $this->getId());
      }

      $elnumber = $this->getElNumber() ? '"'.mysqli_real_escape_string($con, $this->getElNumber()).'"' : 'NULL';
      $code = $this->getCode() ? '"'.mysqli_real_escape_string($con, $this->getCode()).'"' : 'NULL';
      $statusid = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $holderid = $this->getHolderId() ? '"'.mysqli_real_escape_string($con, $this->getHolderId()).'"' : 'NULL';
      $type = $this->getType() ? '"'.mysqli_real_escape_string($con, $this->getType()).'"' : 'NULL';
      $colorid = $this->getColorId() ? '"'.mysqli_real_escape_string($con, $this->getColorId()).'"' : 'NULL';
      $comment = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      $sql = '
         REPLACE doorkey
         SET
            elnumber = '.$elnumber.',
            code = '.$code.',
            status = '.$statusid.',
            holder = '.$holderid.',
            type = '.$type.',
            color = '.$colorid.',
            comment = '.$comment.'
         '.$idString.'
      ';
      $dbresult = queryDb($con, $sql);
      if ($dbresult) {
         $this->id = mysqli_insert_id($con);
      }
      return $dbresult;
   }

}
