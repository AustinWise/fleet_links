<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Hits.aspx.cs" Inherits="Hits"
    EnableViewState="false" %>

<html>
<head runat="server">
    <title>Hits</title>
</head>
<body>
    <h1>
        <a href="FleetLinks.aspx">Fleet Links</a></h1>
    <h2>
        Hits</h2>
    <form id="form1" runat="server">
        <div>
            Get out spy.
            <asp:GridView ID="gv" runat="server" AutoGenerateColumns="False">
                <Columns>
                    <asp:BoundField DataField="Time" HeaderText="Time" />
                    <asp:TemplateField HeaderText="Corperation">
                        <ItemTemplate>
                            <a href='showinfo:2//<%# DataBinder.Eval(Container.DataItem, "CorpId") %>'>
                                <%# DataBinder.Eval(Container.DataItem, "CorpName") %>
                            </a>
                        </ItemTemplate>
                    </asp:TemplateField>
                    <asp:TemplateField HeaderText="Alliance">
                        <ItemTemplate>
                            <a href='showinfo:16159//<%# DataBinder.Eval(Container.DataItem, "AllianceId") %>'>
                                <%# DataBinder.Eval(Container.DataItem, "AllianceName") %>
                            </a>
                        </ItemTemplate>
                    </asp:TemplateField>
                    <asp:BoundField DataField="SolarSystem" HeaderText="Solar System" />
                    <asp:BoundField DataField="NearestLocation" HeaderText="Nearest" />
                    <asp:TemplateField HeaderText="Character">
                        <ItemTemplate>
                            <a href="showinfo:1377//<%# DataBinder.Eval(Container.DataItem, "CharacterId") %>">
                                <%# DataBinder.Eval(Container.DataItem, "CharacterName") %>
                            </a>
                        </ItemTemplate>
                    </asp:TemplateField>
                    <asp:HyperLinkField DataNavigateUrlFields="PageName" DataTextField="PageName" HeaderText="Page" />
                </Columns>
            </asp:GridView>
        </div>
    </form>
</body>
</html>
