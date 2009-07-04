using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using System.Configuration;
using System.Web.Configuration;
using System.Security.Cryptography;
using System.Text;

/// <summary>
/// A fleet.
/// </summary>
public class Fleet
{
    private Fleet()
    {
    }

    /// <summary>
    /// Adds the fleet to the database.
    /// </summary>
    /// <param name="id">The gang id obtained from the link to the fleet.</param>
    /// <param name="allianceId">Members of this alliance can join the fleet.</param>
    /// <param name="name">A sanitized string</param>
    /// <param name="deletePassword">The delete password.</param>
    public static void AddFleet(long id, long allianceId, string name, string deletePassword)
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("CreateFleet", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@id", id);
                cmd.Parameters.AddWithValue("@allianceId", allianceId);
                cmd.Parameters.AddWithValue("@name", name);
                cmd.Parameters.Add("@now", SqlDbType.DateTime).Value = DateTime.UtcNow;
                cmd.Parameters.AddWithValue("@deletePassword", hashPassword(deletePassword));

                cmd.ExecuteNonQuery();
            }
        }
    }

    /// <summary>
    /// Get fleets that belong to one alliance.
    /// </summary>
    /// <param name="allianceId"></param>
    /// <returns></returns>
    public static List<Fleet> GetFleetsForAlliance(long allianceId)
    {
        List<Fleet> fleets = new List<Fleet>();
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("GetAllianceCurrentFleets", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@allianceId", allianceId);

                cmd.Parameters.AddWithValue("@after", Utilities.LastDowntimeMidpoint);

                using (SqlDataReader r = cmd.ExecuteReader())
                {
                    while (r.Read())
                    {
                        Fleet f = fillFleet(r);
                        fleets.Add(f);
                    }
                }
            }
        }

        return fleets;
    }

    /// <summary>
    /// Get one fleet.
    /// </summary>
    /// <param name="id"></param>
    /// <returns></returns>
    public static Fleet GetFleet(long id)
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("GetFleet", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@id", id);

                using (SqlDataReader r = cmd.ExecuteReader())
                {
                    if (!r.Read())
                        throw new ArgumentException("Invalid fleet name.", "id");
                    return fillFleet(r);
                }
            }
        }
    }

    private static Fleet fillFleet(SqlDataReader r)
    {
        Fleet f;
        f = new Fleet();
        f.ID = r.GetInt64(0);
        f.AllianceID = r.GetInt64(1);
        f.Name = r.GetString(2);
        f.Added = r.GetDateTime(3);
        return f;
    }

    /// <summary>
    /// Delete fleets create before the last nightly downtime.
    /// </summary>
    public static void DeleteOldFleets()
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("DeleteOldFleets", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.Add("@before", SqlDbType.DateTime).Value = Utilities.LastDowntimeMidpoint;
                cmd.ExecuteNonQuery();
            }
        }
    }

    /// <summary>
    /// Delete a fleet.
    /// </summary>
    /// <param name="id">The ID of the fleet.</param>
    /// <param name="password">The password to delete the fleet with.</param>
    public static void DeleteFleet(long id, string password)
    {
        using (SqlConnection conn = new SqlConnection(Utilities.ConnStr))
        {
            conn.Open();
            using (SqlCommand cmd = new SqlCommand("DeleteFleet", conn))
            {
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.AddWithValue("@id", id);
                cmd.Parameters.AddWithValue("@password", hashPassword(password));
                cmd.ExecuteNonQuery();
            }
        }
    }

    private static string hashPassword(string password)
    {
        byte[] stringBytes = Encoding.Unicode.GetBytes(password);
        SHA1 sha = SHA1.Create();
        byte[] hashBytes = sha.ComputeHash(stringBytes);
        return Convert.ToBase64String(hashBytes, Base64FormattingOptions.None);
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

    private long allianceId;
    public long AllianceID
    {
        get { return this.allianceId; }
        set { this.allianceId = value; }
    }

    private DateTime added;
    public DateTime Added
    {
        get { return this.added; }
        set { this.added = value; }
    }
}
