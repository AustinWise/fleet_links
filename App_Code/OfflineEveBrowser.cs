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
/// Basicly a mock object for the EVE Browser to be used when testing outside of the EVE IGB.
/// </summary>
public class OfflineEveBrowser : IEveBrowser
{
    public OfflineEveBrowser()
    {
    }

    public bool IsIGB
    {
        get { return true; }
    }

    public bool IsTrusted
    {
        get { return true; }
    }

    public bool RequireTrust()
    {
        return true;
    }

    public string RegionName
    {
        get { return "Delve"; }
    }

    public string ConstellationName
    {
        get { return "W-4U1E"; }
    }

    public string SolarSystemName
    {
        get { return "QY6-RK"; }
    }

    public long AllianceID
    {
        get { return 824518128; }
    }

    public string AllianceName
    {
        get { return "GoonSwarm"; }
    }

    public int CharacterID
    {
        get { return 1164427832; }
    }

    public string CharacterName
    {
        get { return "WoogyDude"; }
    }

    public int CorporationID
    {
        get { return 749147334; }
    }

    public string CorporationName
    {
        get { return "GoonFleet"; }
    }

    public string StationName
    {
        get { return "None"; }
    }

    public IPEndPoint Server
    {
        get { return new IPEndPoint(IPAddress.Parse("87.237.38.200"), 26000); }
    }

    public long CorporationRole
    {
        get { return 2199024312320; }
    }

    public string NearestLocation
    {
        get { return "QY6-RK VI - Moon 24"; }
    }
}
