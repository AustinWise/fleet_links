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
      return EveBrowser::getHeader('HTTP_EVE_REGIONNAME');
   }

   public function ConstellationName() {
      return EveBrowser::getHeader('HTTP_EVE_CONSTELLATIONNAME');
   }

   public function SolarSystemName() {
      return EveBrowser::getHeader('HTTP_EVE_SOLARSYSTEMNAME');
   }

   public function AllianceID() {
      return (int)EveBrowser::getHeader('HTTP_EVE_ALLIANCEID');
   }

   public function AllianceName() {
      return EveBrowser::getHeader('HTTP_EVE_ALLIANCENAME');
   }

   public function CharacterID() {
      return (int)EveBrowser::getHeader('HTTP_EVE_CHARID');
   }

   public function CharacterName() {
      return EveBrowser::getHeader('HTTP_EVE_CHARNAME');
   }

   public function CorporationID() {
      return (int)EveBrowser::getHeader('HTTP_EVE_CORPID');
   }

   public function CorporationName() {
      return EveBrowser::getHeader('HTTP_EVE_CORPNAME');
   }

   public function StationName() {
      return EveBrowser::getHeader('HTTP_EVE_STATIONNAME');
   }

   public function Server() {
      return EveBrowser::getHeader('HTTP_EVE_SERVERIP');
   }

   public function CorporationRole() {
      return (int)EveBrowser::getHeader('HTTP_EVE_CORPROLE');
   }

   public function NearestLocation() {
      return EveBrowser::getHeader('HTTP_EVE_NEARESTLOCATION');
   }
   
   private function getHeader($name) {
      $value = $_SERVER[$name];
      return get_magic_quotes_gpc() ? stripslashes($value) : $value;
   }

}

?>