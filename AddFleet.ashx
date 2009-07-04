<%@ WebHandler Language="C#" Class="AddFleet" %>

using System;
using System.IO;
using System.Web;
using System.Text.RegularExpressions;

public class AddFleet : IHttpHandler
{

    private static Regex rGetGangId = new Regex(@"gang:(?<id>\d+)", RegexOptions.IgnoreCase | RegexOptions.ExplicitCapture);

    public void ProcessRequest(HttpContext context)
    {
        context.Response.ContentType = "text/html";

        IEveBrowser brow = Utilities.EveBrowser;

        bool trusted = false;
        if (brow.IsIGB)
            trusted = brow.RequireTrust();

        if (!trusted)
            context.Response.Redirect("FleetLinks.aspx");


        string name = context.Request.Form["name"];
        string fleetLink = context.Request.Form["fleetLink"];
        string deletePassword = "lame"; //context.Request.Form["deletePassword"];

        if (string.IsNullOrEmpty(name) || string.IsNullOrEmpty(fleetLink) || string.IsNullOrEmpty(deletePassword))
        {
            render(brow, context, brow.AllianceName);
            return;
        }

        if (name.Trim().Length == 0)
        {
            render(brow, context, brow.AllianceName);
            return;
        }

        Match m = rGetGangId.Match(fleetLink);
        Group g = m.Groups["id"];

        if (!g.Success)
        {
            render(brow, context, brow.AllianceName);
            return;
        }

        long fleetId = long.Parse(g.Value);

        Alliance a = Alliance.EnsureAlliance(brow.AllianceID, Utilities.CleanString(brow.AllianceName, 50));

        try
        {
            Fleet.AddFleet(fleetId, a.ID, Utilities.CleanString(name, 50), deletePassword);
        }
        catch
        {
        }

        context.Response.Redirect("FleetLinks.aspx");
    }

    private void render(IEveBrowser brow, HttpContext context, string allianceName)
    {
        string contents = File.ReadAllText(Path.Combine(context.Request.PhysicalApplicationPath, "AddFleet.html"));
        context.Response.Write(string.Format(contents, Utilities.CleanString(allianceName, 50)));
        Hit.RecordHit(brow, "~/AddFleet.ashx");
    }

    public bool IsReusable
    {
        get
        {
            return true;
        }
    }

}