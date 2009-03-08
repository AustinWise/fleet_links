<?php
require_once('IEveBrowser.php');

class EveBrowser implements IEveBrowser {
   public function IsIGB() {
      return (strpos($_SERVER['HTTP_USER_AGENT'], 'EVE-minibrowser/') === 0);
   }

   public function IsTrusted() {
      return ($_SERVER['HTTP_EVE_TRUSTED'] == 'yes');
   }

   public function RequireTrust() {
      header('eve.trustMe:http://' . $_SERVER['HTTP_HOST'] . '/::This site needs trust to function.');
   }

   public function RegionName() {
      return $_SERVER['HTTP_EVE_REGIONNAME'];
   }

   public function ConstellationName() {
      return $_SERVER['HTTP_EVE_CONSTELLATIONNAME'];
   }

   public function SolarSystemName() {
      return $_SERVER['HTTP_EVE_SOLARSYSTEMNAME'];
   }

   public function AllianceID() {
      return (int)$_SERVER['HTTP_EVE_ALLIANCEID'];
   }

   public function AllianceName() {
      return $_SERVER['HTTP_EVE_ALLIANCENAME'];
   }

   public function CharacterID() {
      return (int)$_SERVER['HTTP_EVE_CHARID'];
   }

   public function CharacterName() {
      return $_SERVER['HTTP_EVE_CHARNAME'];
   }

   public function CorporationID() {
      return (int)$_SERVER['HTTP_EVE_CORPID'];
   }

   public function CorporationName() {
      return $_SERVER['HTTP_EVE_CORPNAME'];
   }

   public function StationName() {
      return $_SERVER['HTTP_EVE_STATIONNAME'];
   }

   public function Server() {
      return $_SERVER['HTTP_EVE_SERVERIP'];
   }

   public function CorporationRole() {
      return (int)$_SERVER['HTTP_EVE_CORPROLE'];
   }

   public function NearestLocation() {
      return $_SERVER['HTTP_EVE_NEARESTLOCATION'];
   }

}

?>