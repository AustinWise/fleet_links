/****** Object:  StoredProcedure [dbo].[DeleteOldFleets]    Script Date: 05/02/2009 04:33:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-18
-- Description:	Deletes old fleets.
-- =============================================
CREATE PROCEDURE [DeleteOldFleets] 
	-- Add the parameters for the stored procedure here
	@before DateTime
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

	DELETE FROM Fleets WHERE Added < @before
END
GO
/****** Object:  Table [dbo].[Alliances]    Script Date: 05/02/2009 04:33:34 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [Alliances](
	[ID] [bigint] NOT NULL,
	[Name] [nvarchar](50) NOT NULL,
 CONSTRAINT [PK_Alliances] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Hits]    Script Date: 05/02/2009 04:33:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [Hits](
	[ID] [uniqueidentifier] NOT NULL,
	[Time] [datetime] NOT NULL,
	[CorpID] [bigint] NOT NULL,
	[CorpName] [nvarchar](50) NOT NULL,
	[AllianceID] [bigint] NULL,
	[AllianceName] [nvarchar](50) NULL,
	[SolarSystem] [nvarchar](20) NOT NULL,
	[NearestLocation] [nvarchar](30) NULL,
	[CharacterID] [bigint] NOT NULL,
	[CharacterName] [nvarchar](20) NOT NULL,
	[PageName] [nvarchar](20) NOT NULL,
 CONSTRAINT [PK_Hits] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Fleets]    Script Date: 05/02/2009 04:33:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [Fleets](
	[ID] [bigint] NOT NULL,
	[AllianceID] [bigint] NOT NULL,
	[Name] [nvarchar](50) NOT NULL,
	[Added] [datetime] NOT NULL,
	[DeletePassword] [nchar](28) NOT NULL,
	[IsDeleted] [bit] NOT NULL CONSTRAINT [DF_Fleets_IsDelete]  DEFAULT ((0)),
 CONSTRAINT [PK_Fleets] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  StoredProcedure [dbo].[EnsureAlliance]    Script Date: 05/02/2009 04:33:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-15
-- Description:	Creates an alliance if it does not already exist.
-- =============================================
CREATE PROCEDURE [EnsureAlliance] 
	-- Add the parameters for the stored procedure here
	@id bigint, 
	@name nvarchar(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;


--make sure that the relationship does not already exist
DECLARE @count int
SELECT @count = COUNT(ID) FROM Alliances WHERE ID = @id
IF @count <> 0
	RETURN

INSERT INTO Alliances (ID, Name) VALUES (@id, @name)

END
GO
/****** Object:  StoredProcedure [dbo].[GetAlliancesOtherThanMineWithFleets]    Script Date: 05/02/2009 04:33:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-15
-- Description:	
-- =============================================
CREATE PROCEDURE [GetAlliancesOtherThanMineWithFleets] 
	-- Add the parameters for the stored procedure here
	@myAlliance bigint, 
	@after DateTime
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

	SELECT * FROM Alliances WHERE
		ID in (SELECT AllianceID FROM Fleets WHERE IsDeleted = 0 AND Added > @after)
		AND ID != @myAlliance
END
GO
/****** Object:  StoredProcedure [dbo].[GetAllianceCurrentFleets]    Script Date: 05/02/2009 04:33:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-15
-- Description:	
-- =============================================
CREATE PROCEDURE [GetAllianceCurrentFleets] 
	-- Add the parameters for the stored procedure here
	@allianceId bigint,
	@after DateTime
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    SELECT ID, AllianceID, Name, Added FROM Fleets
	WHERE AllianceID = @allianceId AND Added > @after
		AND IsDeleted = 0
	ORDER BY Added DESC
	
END
GO
/****** Object:  StoredProcedure [dbo].[DeleteFleet]    Script Date: 05/02/2009 04:33:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-18
-- Description:	Deletes a fleet
-- =============================================
CREATE PROCEDURE [DeleteFleet] 
	-- Add the parameters for the stored procedure here
	@id bigint, 
	@password nchar(28)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

	DELETE FROM Fleets WHERE ID = @id AND DeletePassword = @password
END
GO
/****** Object:  StoredProcedure [dbo].[GetFleet]    Script Date: 05/02/2009 04:33:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-18
-- Description:	Gets a fleet
-- =============================================
CREATE PROCEDURE [GetFleet] 
	-- Add the parameters for the stored procedure here
	@id bigint
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

	SELECT ID, AllianceID, Name, Added FROM Fleets WHERE ID = @id
END
GO
/****** Object:  StoredProcedure [dbo].[CreateFleet]    Script Date: 05/02/2009 04:33:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-03-15
-- Description:	
-- =============================================
CREATE PROCEDURE [CreateFleet] 
	-- Add the parameters for the stored procedure here
	@id bigint, 
	@allianceId bigint,
	@name nvarchar(50),
	@now DateTime,
	@deletePassword nchar(28)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    INSERT INTO Fleets (ID, AllianceID, Name, Added, DeletePassword, IsDeleted) VALUES (@id, @allianceId, @name, @now, @deletePassword, 0)
END
GO
/****** Object:  StoredProcedure [dbo].[GetHits]    Script Date: 05/02/2009 04:33:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-04-14
-- Description:	Gets some hits
-- =============================================
CREATE PROCEDURE [GetHits] 
	-- Add the parameters for the stored procedure here
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	SELECT * FROM Hits ORDER BY [Time] DESC
END
GO
/****** Object:  StoredProcedure [dbo].[DeleteOldHits]    Script Date: 05/02/2009 04:33:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2009-02-10
-- Description:	
-- =============================================
CREATE PROCEDURE [DeleteOldHits] 
	-- Add the parameters for the stored procedure here
	@before datetime
AS
BEGIN
	DELETE FROM Hits WHERE Time < @before
END
GO
/****** Object:  StoredProcedure [dbo].[RecordHit]    Script Date: 05/02/2009 04:33:34 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Austin Wise
-- Create date: 2008-04-08
-- Description:	Records a hit
-- =============================================
CREATE PROCEDURE [RecordHit] 
	-- Add the parameters for the stored procedure here
	@corpId bigint
	,@corpName nvarchar(50)
	,@AllianceID bigint
	,@AllianceName nvarchar(50)
	,@SolarSystem nvarchar(20)
	,@NearestLocation nvarchar(30)
	,@CharacterID bigint
	,@CharacterName nvarchar(20)
	,@PageName nvarchar(20)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	INSERT INTO [Main].[dbo].[Hits]
           ([ID]
           ,[Time]
           ,[CorpID]
           ,[CorpName]
           ,[AllianceID]
           ,[AllianceName]
           ,[SolarSystem]
           ,[NearestLocation]
           ,[CharacterID]
           ,[CharacterName]
           ,[PageName])
     VALUES
           (newid()
           ,GETUTCDATE()
           ,@CorpID
           ,@CorpName
           ,@AllianceID
           ,@AllianceName
           ,@SolarSystem
           ,@NearestLocation
           ,@CharacterID
           ,@CharacterName
           ,@PageName)
END
GO
/****** Object:  ForeignKey [FK_Fleets_Alliances]    Script Date: 05/02/2009 04:33:36 ******/
ALTER TABLE [Fleets]  WITH CHECK ADD  CONSTRAINT [FK_Fleets_Alliances] FOREIGN KEY([AllianceID])
REFERENCES [Alliances] ([ID])
GO
ALTER TABLE [Fleets] CHECK CONSTRAINT [FK_Fleets_Alliances]
GO
