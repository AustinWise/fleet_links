using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Net;

/// <summary>
/// Represents the type of data that the EVE IGB will send.
/// </summary>
public interface IEveBrowser
{
    bool IsIGB { get; }
    bool IsTrusted { get; }
    bool RequireTrust();
    string RegionName { get; }
    string ConstellationName { get; }
    string SolarSystemName { get; }
    long AllianceID { get; }
    string AllianceName { get; }
    int CharacterID { get; }
    string CharacterName { get; }
    int CorporationID { get; }
    string CorporationName { get; }
    string StationName { get; }
    IPEndPoint Server { get; }
    long CorporationRole { get; }
    string NearestLocation { get; }
}
