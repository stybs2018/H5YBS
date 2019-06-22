USE h5ybs;

--
-- Table for Admin Role
--
DROP TABLE IF EXISTS admin_role;

CREATE TABLE admin_role (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    status tinyint(4) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO admin_role (id, name) VALUES(1, '超级管理员');

--
-- Table for Admin Permission
--

DROP TABLE IF EXISTS admin_permission;

CREATE TABLE admin_permission (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    type varchar(32) NOT NULL,
    name varchar(255) NOT NULL,
    _icon varchar(255),
    _route varchar(255) NOT NULL,   
    _pid int(11) unsigned NOT NULL DEFAULT 0,
    _sort tinyint(4) NOT NULL DEFAULT 0,
    _method varchar(32),
    _module varchar(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (1, 'Menu', '系统设置', 'layui-icon-set', '#', 0, 99);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (2, 'Menu', '权限设置', '#', '#', 1, 99);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (3, 'Menu', '管理员', '#', '/admin', 2, 1);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (4, 'Menu', '管理组', '#', '/admin/role', 2, 2);

--
-- Table for Admin Assign
--
DROP TABLE IF EXISTS admin_assign;

CREATE TABLE admin_assign (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    role int(11) unsigned NOT NULL DEFAULT 1,
    permission int(11) unsigned NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO admin_assign (role, permission) SELECT 1,id FROM admin_permission;

--
-- Table for Admin
--
DROP TABLE IF EXISTS admin;

CREATE TABLE admin (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(32) NOT NULL UNIQUE,
    password varchar(60),
    role int(11) unsigned NOT NULL,
    nickname varchar(255) NOT NULL,
    telephone varchar(32),
    avatar varchar(255) DEFAULT NULL,
    created_at dateTime DEFAULT NULL,
    updated_at dateTime DEFAULT NULL,
    logined_at dateTime DEFAULT NULL,
    status tinyint(4) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO admin (username, role, nickname) VALUES ('admin', 1, '管理员');