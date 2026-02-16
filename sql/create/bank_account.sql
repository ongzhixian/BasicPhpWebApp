USE [PineappleFinance]
GO

/****** Object:  Table [dbo].[bank_account]    Script Date: 2026-02-11 21:50:02 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[bank_account](
	[id] [tinyint] IDENTITY(1,1) NOT NULL,
	[bank_id] [tinyint] NOT NULL,
	[account_code] [varchar](50) NOT NULL,
	[description] [varchar](128) NOT NULL,
	[balance] [decimal](18, 2) NOT NULL,
	[create_at] [datetime2](7) NOT NULL,
	[update_at] [datetime2](7) NOT NULL,
 CONSTRAINT [PK_bank_account] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

SET ANSI_PADDING ON
GO

/****** Object:  Index [IX_bank_account]    Script Date: 2026-02-11 21:50:02 ******/
CREATE UNIQUE NONCLUSTERED INDEX [IX_bank_account] ON [dbo].[bank_account]
(
	[account_code] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO

ALTER TABLE [dbo].[bank_account] ADD  CONSTRAINT [DF_bank_account_description]  DEFAULT ('') FOR [description]
GO

ALTER TABLE [dbo].[bank_account] ADD  CONSTRAINT [DF_bank_account_create_at]  DEFAULT (getdate()) FOR [create_at]
GO

ALTER TABLE [dbo].[bank_account] ADD  CONSTRAINT [DF_bank_account_update_at]  DEFAULT (getdate()) FOR [update_at]
GO

ALTER TABLE [dbo].[bank_account]  WITH CHECK ADD  CONSTRAINT [FK_bank_account_bank] FOREIGN KEY([bank_id])
REFERENCES [dbo].[bank] ([id])
GO

ALTER TABLE [dbo].[bank_account] CHECK CONSTRAINT [FK_bank_account_bank]
GO


