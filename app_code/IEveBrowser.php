<?php
interface IEveBrowser {
   function IsIGB();
   function IsTrusted();
   function RequireTrust();
   function RegionName();
   function ConstellationName();
   function SolarSystemName();
   function AllianceID();
   function AllianceName();
   function CharacterID();
   function CharacterName();
   function CorporationID();
   function CorporationName();
   function StationName();
   function Server();
   function CorporationRole();
   function NearestLocation();
}
?>