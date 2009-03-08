<?php
require_once('IEveBrowser.php');

class OfflineEveBrowser implements IEveBrowser {
   public function IsIGB() {
      return 1;
   }

   public function IsTrusted() {
      return 1;
   }

   public function RequireTrust() {
   }

   public function RegionName() {
      return 'Delve';
   }

   public function ConstellationName() {
      return "W-4U1E";
   }

   public function SolarSystemName() {
      return "QY6-RK";
   }

   public function AllianceID() {
      return 824518128;
   }

   public function AllianceName() {
      return "GoonSwarm";
   }

   public function CharacterID() {
      return 1164427832;
   }

   public function CharacterName() {
      return "WoogyDude";
   }

   public function CorporationID() {
      return 749147334;
   }

   public function CorporationName() {
      return "GoonFleet";
   }

   public function StationName() {
      return "None";
   }

   public function Server() {
      return "87.237.38.200:26000";
   }

   public function CorporationRole() {
      return 2199024312320;
   }

   public function NearestLocation() {
      return "QY6-RK VI - Moon 24";
   }
}

?>