<%@ Page Language="C#" AutoEventWireup="true" CodeFile="FleetLinks.aspx.cs" Inherits="FleetLinks" EnableViewState="false"%>

<html>
<head>
    <title>Fleet Links</title>
    <meta http-equiv="refresh" content="10" >
</head>
<body>
    <form id="form1" runat="server">
        <asp:Label ID="lblOogWarning" ForeColor="red" runat="server" Visible="false" Font-Size="XX-Large">This page is designed to be viewed in the EVE in-game browser.</asp:Label>
        <asp:Label ID="lblTrustedWarning" ForeColor="Red" runat="server" Visible="false" Font-Size="XX-Large">You must add this site to your trusted site list. <a href="MakeTrusted.html">Instructions here.</a></asp:Label>
        <h1>
            Fleet Links
        </h1>
        <asp:Panel ID="panMyFleets" runat="server">
            <a href="AddFleet.ashx">Add a fleet</a>
            <h2>
                <asp:Literal ID="litAllianceName" runat="server" />
            </h2>
            <asp:Repeater ID="repUs" runat="server">
                <ItemTemplate>
                    <%# DataBinder.Eval(Container.DataItem, "Added", "{0:HH:mm}") %>
                    : <a href='gang:<%# DataBinder.Eval(Container.DataItem, "ID") %>'><%# DataBinder.Eval(Container.DataItem, "Name") %></a>
                    <a href='DeleteFleet.ashx?id=<%# DataBinder.Eval(Container.DataItem, "ID") %>'>[delete]</a>
                    <br />
                </ItemTemplate>
            </asp:Repeater>
        </asp:Panel>
        <h2>
            Other Alliances</h2>
        <asp:Repeater ID="repThem" runat="server">
            <ItemTemplate>
                <b>
                    <%# DataBinder.Eval(Container.DataItem,"Name") %>
                </b>
                <br />
                <asp:Repeater runat="server" DataSource='<%# DataBinder.Eval(Container.DataItem, "ActiveFleets") %>'>
                    <ItemTemplate>
                        <%# DataBinder.Eval(Container.DataItem, "Added", "{0:HH:mm}") %>
                        :
                        <%# DataBinder.Eval(Container.DataItem, "Name") %>
                        <br />
                    </ItemTemplate>
                </asp:Repeater>
            </ItemTemplate>
        </asp:Repeater>
        <hr />
        <small>Feel free contact <a href="showinfo:1376//1164427832">WoogyDude</a> in game or via <a href="http://www.goonfleet.com/member.php?u=22552">GoonFleet private message</a> if you have any questions or comments.</small>
        <hr />
        <asp:Literal ID="litTest" runat="server" />
    </form>
</body>
</html>
