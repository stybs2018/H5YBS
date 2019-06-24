USE h5ybs;

ALTER TABLE customer ADD subscribe tinyint(4) DEFAULT 0 AFTER unionid;
ALTER TABLE customer ADD subscribe_time datetime NULL AFTER subscribe;
ALTER TABLE customer ADD subscribe_scene varchar(255) NULL AFTER subscribe_time;

-- 
-- Table for Wx Service Account
-- 

CREATE TABLE wxs_ad (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    sum int(11) NOT NULL DEFAULT 0,
    addition int(11) NOT NULL DEFAULT 0,
    subtraction int(11) NOT NULL DEFAULT 0,
    diff  int(11) NOT NULL DEFAULT 0,
    user_source text,
    created_at date,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- 
-- Table for Wx Menu
-- 

CREATE TABLE wxs_menu (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    type varchar(32) NOT NULL DEFAULT 'view',
    name varchar(32) NOT NULL,
    _url varchar(255),
    _key varchar(255),
    _media_id varchar(255),
    _appid varchar(255),
    _pagepath varchar(255),
    pid int(11) unsigned NOT NULL DEFAULT 0,
    status tinyint(4) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;