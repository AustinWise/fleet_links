using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using System.Configuration;
using System.Web.Configuration;

/// <summary>
/// Represents an alliance
/// </summary>
public class Alliance
{
    private Alliance()
    {
    }

    /// <summary>
    /// Make sure that a database record exist for the specified alliance.
    /// </summary>
    /// <param name="id">The ID of alliance.</param>
    /// <param name="name">The sanitized name of the alliance.</param>
    /// <returns></returns>
    public static Alliance EnsureAlliance(long id, string name)
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("EnsureAlliance", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@id", id);
                cmd.Parameters.AddWithValue("@name", name);

                cmd.ExecuteNonQuery();
            }
        }

        Alliance a = new Alliance();
        a.ID = id;
        a.name = name;
        return a;
    }

    /// <summary>
    /// Gets alliances other than the specified one that have active fleets running.
    /// </summary>
    /// <param name="id">The ID of the alliance to exclude.</param>
    /// <returns></returns>
    public static List<Alliance> GetAlliancesOtherThanMineWithFleets(long id)
    {
        List<Alliance> alliances = new List<Alliance>();
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("GetAlliancesOtherThanMineWithFleets", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@myAlliance", id);
                cmd.Parameters.AddWithValue("@after", Utilities.LastDowntimeMidpoint);

                using (SqlDataReader r = cmd.ExecuteReader())
                {
                    while (r.Read())
                    {
                        Alliance a = new Alliance();
                        a.ID = r.GetInt64(0);
                        a.Name = r.GetString(1);
                        alliances.Add(a);
                    }
                }
            }
        }

        return alliances;
    }

    /// <summary>
    /// Get active fleets for thing alliance.
    /// </summary>
    public List<Fleet> ActiveFleets
    {
        get
        {
            return Fleet.GetFleetsForAlliance(this.ID);
        }
    }

    private long id;
    public long ID
    {
        get { return this.id; }
        set { this.id = value; }
    }

    private string name;
    public string Name
    {
        get { return this.name; }
        set { this.name = value; }
    }
}
