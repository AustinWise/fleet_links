using System;
using System.Collections.Generic;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Data.SqlClient;
using System.Web.Configuration;

/// <summary>
/// Represents a hit to a webapge.
/// </summary>
public class Hit
{
    private Hit()
    {
    }

    /// <summary>
    /// Records a hit on the site.  Sanitizes input.
    /// </summary>
    /// <param name="brow"></param>
    /// <param name="pageName"></param>
    public static void RecordHit(IEveBrowser brow, string pageName)
    {
        if (!(brow.IsIGB && brow.IsTrusted))
            return;


        long? allianceId;
        string allianceName;
        try
        {
            allianceId = brow.AllianceID;
            allianceName = brow.AllianceName;
        }
        catch
        {
            allianceId = null;
            allianceName = null;
        }

        try
        {
            recordHitImpel(pageName,
                           brow.CharacterID,
                           Utilities.CleanString(brow.CharacterName, 20),
                           brow.CorporationID,
                           Utilities.CleanString(brow.CorporationName, 50),
                           allianceId,
                           Utilities.CleanString(allianceName, 50),
                           Utilities.CleanString(brow.SolarSystemName, 20),
                           Utilities.CleanString(brow.NearestLocation, 30));
        }
        catch (SqlException ex)
        {
            throw;
        }
        catch (Exception ex)
        {
            //Eat exceptions that are caused by bad input
        }
    }

    /// <summary>
    /// Actually adds record to the database, does not check input.
    /// </summary>
    private static void recordHitImpel(string pageName, long characterId, string characterName, long corpId, string corpName, long? allianceId, string allianceName, string solarSystem, string nearestLocation)
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("RecordHit", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@corpId", corpId);
                cmd.Parameters.AddWithValue("@corpName", corpName);
                cmd.Parameters.AddWithValue("@AllianceID", allianceId);
                cmd.Parameters.AddWithValue("@AllianceName", allianceName);
                cmd.Parameters.AddWithValue("@SolarSystem", solarSystem);
                cmd.Parameters.AddWithValue("@NearestLocation", nearestLocation);
                cmd.Parameters.AddWithValue("@CharacterID", characterId);
                cmd.Parameters.AddWithValue("@CharacterName", characterName);
                cmd.Parameters.AddWithValue("@PageName", pageName);

                cmd.ExecuteNonQuery();
            }
        }
    }

    /// <summary>
    /// Get the hits.
    /// </summary>
    /// <returns></returns>
    public static List<Hit> GetHits()
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("GetHits", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;

                using (SqlDataReader reader = cmd.ExecuteReader())
                {
                    List<Hit> hits = new List<Hit>();
                    while (reader.Read())
                    {
                        Hit h = new Hit();
                        h.id = reader.GetGuid(0);
                        h.time = reader.GetDateTime(1);
                        h.corpId = reader.GetInt64(2);
                        h.corpName = reader.GetString(3);
                        h.allianceId = reader.IsDBNull(4) ? new Nullable<long>() : reader.GetInt64(4);
                        h.allianceName = reader.IsDBNull(5) ? null : reader.GetString(5);
                        h.solarSystem = reader.GetString(6);
                        h.nearestLocation = reader.GetString(7);
                        h.characterId = reader.GetInt64(8);
                        h.characterName = reader.GetString(9);
                        h.pageName = reader.GetString(10);
                        hits.Add(h);
                    }
                    return hits;
                }
            }
        }
    }

    /// <summary>
    /// Delete hits recorded more than a month ago.
    /// </summary>
    public static void DeleteOldHits()
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("DeleteOldHits", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.Add("@before", SqlDbType.DateTime).Value = DateTime.UtcNow.Subtract(new TimeSpan(31, 0, 0, 0));
                cmd.ExecuteNonQuery();
            }
        }
    }

    #region Data
    private Guid id;
    public Guid ID
    {
        get { return id; }
    }

    private DateTime time;
    public DateTime Time
    {
        get { return time; }
    }

    private long corpId;
    public long CorpId
    {
        get { return corpId; }
    }

    private string corpName;
    public string CorpName
    {
        get { return corpName; }
    }

    private long? allianceId;
    public long? AllianceId
    {
        get { return allianceId; }
    }

    private string allianceName;
    public string AllianceName
    {
        get { return allianceName; }
    }

    private string solarSystem;
    public string SolarSystem
    {
        get { return solarSystem; }
    }

    private string nearestLocation;
    public string NearestLocation
    {
        get { return nearestLocation; }
    }

    private long characterId;
    public long CharacterId
    {
        get { return characterId; }
    }

    private string characterName;
    public string CharacterName
    {
        get { return characterName; }
    }

    private string pageName;
    public string PageName
    {
        get { return pageName; }
    }
    #endregion




}
