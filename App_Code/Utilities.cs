using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Web.Configuration;

/// <summary>
/// Various usefull things.
/// </summary>
public static class Utilities
{
    /// <summary>
    /// The connection string to the database.
    /// </summary>
    public static string ConnStr
    {
        get
        {
            return WebConfigurationManager.ConnectionStrings["FleetLinks"].ConnectionString;
        }
    }

    /// <summary>
    /// HTML encodes as string and trims it.
    /// </summary>
    /// <param name="str">The string to be cleaned.</param>
    /// <param name="length">The maximum lenghth the string should be.</param>
    /// <returns>A squeaky clean string.</returns>
    public static string CleanString(string str, int length)
    {
        string ret = HttpUtility.HtmlEncode(str.Trim());
        if (ret.Length > length)
            ret = ret.Substring(0, length);
        int ampNdx = ret.LastIndexOf('&');
        if (ampNdx == -1)
            return ret;
        else
            if (ret.IndexOf(';', ampNdx) == -1)
                ret = ret.Substring(0, ampNdx);
        return ret;
    }

    /// <summary>
    /// The middle of the last nightly downtime.
    /// </summary>
    public static DateTime LastDowntimeMidpoint
    {
        get
        {
            DateTime now = DateTime.UtcNow;
            DateTime midDowntime = new DateTime(now.Year, now.Month, now.Day, 11, 5, 0);
            if (now < midDowntime)
            {
                midDowntime = midDowntime.Subtract(new TimeSpan(24, 0, 0));
            }
            return midDowntime;
        }
    }

    private static object sync = new object();
    private static IEveBrowser instance;
    /// <summary>
    /// Returns an instance of <see cref="IEveBrowser"/> that may or may not be a mock object.
    /// See the web.config file for details
    /// </summary>
    public static IEveBrowser EveBrowser
    {
        get
        {
            if (instance == null)
            {
                lock (sync)
                {
                    if (instance == null)
                    {
                        if (bool.Parse(WebConfigurationManager.AppSettings["OfflineMode"]))
                            instance = new OfflineEveBrowser();
                        else
                            instance = new EveBrowser();
                    }
                }
            }
            return instance;
        }
    }
}
