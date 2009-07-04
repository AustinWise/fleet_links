using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Configuration;
using System.Net;
using System.Text.RegularExpressions;

/// <summary>
/// Pulls data from the HTTP headers sent by the EVE IGB.
/// See http://wiki.eve-dev.net/IGB_Documentation for details.
/// </summary>
public class EveBrowser : IEveBrowser
{
    public EveBrowser()
    {
    }

    private HttpRequest Request
    {
        get
        {
            return HttpContext.Current.Request;
        }
    }

    private HttpResponse Response
    {
        get
        {
            return HttpContext.Current.Response;
        }
    }

    public bool IsIGB
    {
        get
        {
            return Request.UserAgent.StartsWith("EVE-minibrowser/", StringComparison.OrdinalIgnoreCase);
        }
    }

    public bool IsTrusted
    {
        get
        {
            if (!IsIGB)
                throw new InvalidOperationException("Currect request is not coming from the EVE IGB.");
            return string.Compare(Request.Headers["Eve.Trusted"], "yes", StringComparison.OrdinalIgnoreCase) == 0;
        }
    }

    public bool RequireTrust()
    {
        if (!IsTrusted)
        {
            Response.AddHeader("eve.trustMe", string.Format("http://{0}/::This site needs trust to function.", Request.Url.Port == 80 ? Request.Url.Host : Request.Url.Host + ":" + Request.Url.Port));
            return false;
        }
        return true;
    }

    public string RegionName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Regionname"];
        }
    }

    public string ConstellationName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Constellationname"];
        }
    }

    public string SolarSystemName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Solarsystemname"];
        }
    }

    public long AllianceID
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return long.Parse(Request.Headers["Eve.Allianceid"]);
        }
    }

    public string AllianceName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Alliancename"];
        }
    }

    public int CharacterID
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return int.Parse(Request.Headers["Eve.Charid"]);
        }
    }

    public string CharacterName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Charname"];
        }
    }

    public int CorporationID
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return int.Parse(Request.Headers["Eve.Corpid"]);
        }
    }

    public string CorporationName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Corpname"];
        }
    }

    public string StationName
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Stationname"];
        }

    }

    public IPEndPoint Server
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            string[] peices = Request.Headers["Eve.Serverip"].Split(':');
            return new IPEndPoint(IPAddress.Parse(peices[0]), int.Parse(peices[1]));
        }
    }

    public long CorporationRole
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return long.Parse(Request.Headers["Eve.Corprole"]);
        }
    }

    public string NearestLocation
    {
        get
        {
            if (!IsTrusted)
                throw new InvalidOperationException("This website is not trusted by the EVE IGB.");
            return Request.Headers["Eve.Nearestlocation"];
        }

    }

    //public enum NearestLocationType
    //{
    //    None,
    //    Planet,
    //    Moon,
    //    AsteroidBelt,
    //    IceBelt,
    //    Stargate
    //}

    ////Eve.Nearestlocation: QY6-RK VI - Moon 24 
    //private Regex rNearestLocationMoon = new Regex(@"(?<system>[a-zA-Z0-9\-]+) (?<planet>[ivxcl]+) - Moon (?<moon>\d+)", RegexOptions.IgnoreCase | RegexOptions.Compiled | RegexOptions.ExplicitCapture);

    ////Eve.Nearestlocation: QY6-RK IV - Asteroid Belt 1 
    //private Regex rNearestLocationBelt = new Regex(@"(?<system>[a-zA-Z0-9\-]+) (?<planet>[ivxcl]+) - Asteroid Belt (?<belt>\d+)", RegexOptions.IgnoreCase | RegexOptions.Compiled | RegexOptions.ExplicitCapture);

    ////this is a gusse
    //private Regex rNearestLocationIceBelt = new Regex(@"(?<system>[a-zA-Z0-9\-]+) (?<planet>[ivxcl]+) - Asteroid Belt (?<belt>\d+)", RegexOptions.IgnoreCase | RegexOptions.Compiled | RegexOptions.ExplicitCapture);

    ////Eve.Nearestlocation: QY6-RK IV 
    //private Regex rNearestLocationPlanet = new Regex(@"(?<system>[a-zA-Z0-9\-]+) (?<planet>[ivxcl]+)", RegexOptions.IgnoreCase | RegexOptions.Compiled | RegexOptions.ExplicitCapture);

    ////Eve.Nearestlocation: Stargate (J-LPX7) 
    //private Regex rNearestLocationStargate = new Regex(@"Stargate \((?<system>[a-zA-Z0-9\-]+)\)", RegexOptions.IgnoreCase | RegexOptions.Compiled | RegexOptions.ExplicitCapture);

    ////Eve.Nearestlocation: None 
    ////Eve.Stationname: QY6 We built this city on buttes and lol

    //public NearestLocationType TypeOfNearLocation
    //{
    //    get
    //    {
    //        string nearest = NearestLocation;
    //        if (string.Compare("None", nearest, StringComparison.OrdinalIgnoreCase) == 0)
    //            return NearestLocationType.None;
    //        else if (rNearestLocationStargate.IsMatch(nearest))
    //            return NearestLocationType.Stargate;
    //        else if (rNearestLocationIceBelt.IsMatch(nearest))
    //            return NearestLocationType.IceBelt;
    //        else if (rNearestLocationBelt.IsMatch(nearest))
    //            return NearestLocationType.AsteroidBelt;
    //        else if (rNearestLocationMoon.IsMatch(nearest))
    //            return NearestLocationType.Moon;
    //        else if (rNearestLocationPlanet.IsMatch(nearest))
    //            return NearestLocationType.Planet;
    //        else
    //            throw new Exception("Unknown type");
    //    }
    //}
}
