using System;
using System.Configuration;
using System.Data;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;

using System.Text;
using System.Reflection;

public partial class FleetLinks : System.Web.UI.Page
{
    private bool trusted = false;

    protected void Page_Load(object sender, EventArgs e)
    {
        IEveBrowser brow = Utilities.EveBrowser;
litTest.Text = DateTime.Now.ToString();
        if (brow.IsIGB)
        {
            trusted = brow.RequireTrust();
            lblOogWarning.Visible = false;
        }
        else
        {
            lblOogWarning.Visible = true;
        }


        if (!trusted)
        {
            repThem.DataSource = Alliance.GetAlliancesOtherThanMineWithFleets(0);
            repThem.DataBind();

            lblTrustedWarning.Visible = brow.IsIGB;

            return;
        }
        
        StringBuilder sb = new StringBuilder();
        foreach (PropertyInfo pi in typeof(IEveBrowser).GetProperties()) {
         sb.Append(pi.Name);
         sb.Append(": ");
         sb.Append(pi.GetValue(brow, new object[0]));
         sb.Append("<br />");
        }
         // sb.ToString();

        panMyFleets.Visible = trusted;

        litAllianceName.Text = Utilities.CleanString(brow.AllianceName, 50);

        repUs.DataSource = Fleet.GetFleetsForAlliance(brow.AllianceID);
        repUs.DataBind();

        repThem.DataSource = Alliance.GetAlliancesOtherThanMineWithFleets(brow.AllianceID);
        repThem.DataBind();

        Hit.RecordHit(brow, "~/FleetLinks.aspx");
    }
}
