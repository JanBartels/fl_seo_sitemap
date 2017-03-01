#
# Table structure for table 'pages'
#
CREATE TABLE pages (
   tx_flseositemap_field tinytext NOT NULL
);

#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
   tx_flseositemap_field tinytext NOT NULL
);

#
# Table structure for table 'tx_flseositemap_pagecounter'
#
CREATE TABLE tx_flseositemap_pagecounter (
        uid int(11) NOT NULL auto_increment,
        pid int(11) DEFAULT '0' NOT NULL,
        tstamp int(11) DEFAULT '0' NOT NULL,
        crdate int(11) DEFAULT '0' NOT NULL,
        cruser_id int(11) DEFAULT '0' NOT NULL,
        page int(11) DEFAULT '0' NOT NULL,
        counter int(11) DEFAULT '0' NOT NULL,

        PRIMARY KEY (uid),
        KEY parent (pid)
);