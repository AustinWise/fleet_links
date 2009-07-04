<%@ WebHandler Language="C#" Class="DeleteFleet" %>

using System;
using System.IO;
using System.Web;

public class DeleteFleet : IHttpHandler
{

    public void ProcessRequest(HttpContext context)
    {
        context.Response.ContentType = "text/html";

        IEveBrowser brow = Utilities.EveBrowser;

        string fleetString = context.Request.QueryString["id"];
        long fleetId;
        if (string.IsNullOrEmpty(fleetString) || !long.TryParse(fleetString, out fleetId))
        {
            context.Response.Redirect("FleetLinks.aspx");
            return;
        }

        Fleet f;
        try
        {
            f = Fleet.GetFleet(fleetId);
        }
        catch
        {
            context.Response.Redirect("FleetLinks.aspx");
            return;
        }

        if (f.AllianceID != brow.AllianceID)
        {
            context.Response.Redirect("FleetLinks.aspx");
            return;
        }

        string password = context.Request.Form["password"];

        if (string.IsNullOrEmpty(password))
        {
            render(brow, context, brow.AllianceName, f.Name, f.ID);
            return;
        }

        Fleet.DeleteFleet(f.ID, password);
        context.Response.Redirect("FleetLinks.aspx");
    }

    private void render(IEveBrowser brow, HttpContext context, string allianceName, string fleetName, long fleetId)
    {
        string contents = File.ReadAllText(Path.Combine(context.Request.PhysicalApplicationPath, "DeleteFleet.html"));
        context.Response.Write(string.Format(contents, fleetName, allianceName, fleetId));
        Hit.RecordHit(brow, "~/DeleteFleet.ashx");
    }

    public bool IsReusable
    {
        get
        {
            return true;
        }
    }

}