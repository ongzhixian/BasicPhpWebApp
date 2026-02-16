USE [PineappleFinance]
GO

/****** Object:  Table [dbo].[bank]    Script Date: 2026-02-11 21:49:47 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[bank](
	[id] [tinyint] IDENTITY(1,1) NOT NULL,
	[name] [varchar](50) NOT NULL,
	[full_name] [varchar](128) NOT NULL,
	[create_at] [datetime2](7) NOT NULL,
	[update_at] [datetime2](7) NOT NULL,
 CONSTRAINT [PK_bank] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

SET ANSI_PADDING ON
GO

/****** Object:  Index [IX_bank]    Script Date: 2026-02-11 21:49:48 ******/
CREATE UNIQUE NONCLUSTERED INDEX [IX_bank] ON [dbo].[bank]
(
	[name] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO

ALTER TABLE [dbo].[bank] ADD  CONSTRAINT [DF_bank_create_at]  DEFAULT (getdate()) FOR [create_at]
GO

ALTER TABLE [dbo].[bank] ADD  CONSTRAINT [DF_bank_update_at]  DEFAULT (getdate()) FOR [update_at]
GO


