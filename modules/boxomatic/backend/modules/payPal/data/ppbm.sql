DROP TABLE IF EXISTS tbl_ppbutton;

CREATE TABLE tbl_ppbutton(
	name VARCHAR(64) NOT NULL,
	web_site_code TEXT,
	email_link TEXT,
	hosted_button_id CHAR(13) NOT NULL, /* Did not find the size of this property in PayPal's documentation */
	PRIMARY KEY(name)
);