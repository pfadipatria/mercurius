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
               key.id,
               key.elnumber,
               key.code,
               key.type,
               key.color AS colorid,
               keycolor.name AS colorname,
               keycolor.display AS colordisplay,
               key.status AS statusid,
               keystatus.name AS statusname,
               key.mech AS mechid,
               keymech.number AS mechnumber,
               keymech.description AS mechdesc,
               keymech.user AS mechuser,
               holder AS holderid,
               dholder AS dholderid,
               key.comment AS comment,
               key.communication,
               key.lastupdate AS lastupdate
        ';

        $this->from = '
            FROM `key`
            LEFT JOIN `keycolor` ON (key.color = keycolor.id)
            LEFT JOIN `keystatus` ON (key.status = keystatus.id)
            LEFT JOIN `keymech` ON (key.mech = keymech.id)
        ';

      $this->where = '
         WHERE key.id = '.$id.'
      ';

        $this->order = '
            ORDER BY key.code
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

      $dholder = null;
      if(!empty($this->dholderid)) {
         $dholder = new \SKeyManager\Entity\Person($this->dholderid);
         $dholder->load();
      } else {
         $dholder = new \SKeyManager\Entity\Person();
      }
      $this->dholder = $dholder;
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

   function getColorName(){
      return $this->colorname;
   }

   function getColorDisplay(){
      return dechex($this->colordisplay);
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

   function getDHolder() {
      return $this->dholder;
   }

   function getDHolderId() {
      return $this->dholderid;
   }

   function setDHolderId($dholderid) {
      $this->dholderid = $dholderid;
      return $this;
   }

   function getDHolderName(){
      return $this->getDHolder()->getName();
   }

   /**
    * For backward compability, to be removed. Its now called holder instead of owner
    */
   function getOwner() {
      return $this->getHolder();
   }

   /**
    * For backward compability, to be removed. Its now called holder instead of owner
    */
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

   function getMechId() {
      return $this->mechid;
   }

   function setMechId($mechId) {
      $this->mechid = $mechId;
      return $this;
   }

   function getMechNumber() {
      return $this->mechnumber;
   }

   function getMechDesc() {
      return $this->mechdesc ? sprintf('%04d', $this->mechdesc) : null ;
   }

   function getMechUser() {
      return $this->mechuser;
   }

   function getCommunication(){
      return $this->communication;
   }

   function setCommunication($communication) {
      $this->communication = $communication;
      return $this;
   }

   function getLastUpdate(){
      return $this->lastupdate;
   }

   function getName() {
      $name = 'MC '.$this->getCode();
      $name .= $this->getHolderName() ? ' - '.$this->getHolderName() : '';
      if(!$this->getHolderName()) {
         $name .= $this->getComment() ? ' - '.$this->getCommentShort() : '';
      }
      return $name;
   }

   function save() {
      $idString = '';
      $dbTable = 'key';
      $con = openDb();

      $data = array();
      $data['elnumber'] = $this->getElNumber() ? '"'.mysqli_real_escape_string($con, $this->getElNumber()).'"' : 'NULL';
      $data['code'] = $this->getCode() ? '"'.mysqli_real_escape_string($con, $this->getCode()).'"' : 'NULL';
      $data['status'] = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $data['holder'] = $this->getHolderId() ? '"'.mysqli_real_escape_string($con, $this->getHolderId()).'"' : 'NULL';
      $data['dholder'] = $this->getDHolderId() ? '"'.mysqli_real_escape_string($con, $this->getDHolderId()).'"' : 'NULL';
      $data['type'] = $this->getType() ? '"'.mysqli_real_escape_string($con, $this->getType()).'"' : 'NULL';
      $data['color'] = $this->getColorId() ? '"'.mysqli_real_escape_string($con, $this->getColorId()).'"' : 'NULL';
      $data['mech'] = $this->getMechId() ? '"'.mysqli_real_escape_string($con, $this->getMechId()).'"' : 'NULL';
      $data['comment'] = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';
      $data['communication'] = 'NULL';
      if($this->getCommunication() == '1') {
         $data['communication'] = '1';
      }
      if($this->getCommunication() == '0') {
         $data['communication'] = '0';
      }

      if($this->getId()) {
         $idString = ' id = '.mysqli_real_escape_string($con, $this->getId());
         return $this->updateDb($con, $dbTable, $data, $idString);
      } else {
         return $this->insertDb($con, $dbTable, $data);
      }
   }

}
