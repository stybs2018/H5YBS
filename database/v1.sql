USE h5ybs;

--
-- Table for Admin Role
--
DROP TABLE IF EXISTS admin;
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
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (3, 'Menu', '管理员', '#', 'admin', 2, 1);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (4, 'Menu', '管理组', '#', 'admin/role', 2, 2);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (5, 'Menu', '客户管理', 'layui-icon-group', '#', 0, 1);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (6, 'Menu', '客户列表', '#', 'customer', 5, 1);
INSERT INTO admin_permission(id, type, name, _icon, _route, _pid, _sort) VALUES (7, 'Menu', '预约管理', '#', 'customer/reserve', 5, 2);

INSERT INTO admin_permission (type, name, _route, _module) VALUES ('Page', '管理员', 'admin', '权限设置');
INSERT INTO admin_permission (type, name, _route, _module) VALUES ('Page', '管理组', 'admin/role', '权限设置');
INSERT INTO admin_permission (type, name, _route, _module) VALUES ('Page', '客户列表', 'customer', '客户管理');
INSERT INTO admin_permission (type, name, _route, _module) VALUES ('Page', '预约管理', 'customer/reserve', '客户管理');

INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '获取', 'admin', '管理员', 'GET');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '创建', 'admin', '管理员', 'POST');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '更新', 'admin', '管理员', 'PUT');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '删除', 'admin', '管理员', 'DELETE');

INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '获取', 'admin/role', '管理组', 'GET');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '创建', 'admin/role', '管理组', 'POST');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '更新', 'admin/role', '管理组', 'PUT');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '删除', 'admin/role', '管理组', 'DELETE');

INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '授权', 'admin/role/assign', '管理组', 'POST');

INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '获取', 'customer', '客户', 'GET');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '更新', 'customer', '客户', 'PUT');

INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '获取', 'customer/reserve', '客户预约', 'GET');
INSERT INTO admin_permission (type, name, _route, _module, _method) VALUES ('Action', '更新', 'customer/reserve', '客户预约', 'PUT');

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
    PRIMARY KEY (id),
    FOREIGN KEY (role) REFERENCES admin_role(id)
) ENGINE=InnoDB;

INSERT INTO admin (username, role, nickname) VALUES ('admin', 1, '管理员');


--
-- Table for Customer
--
DROP TABLE IF EXISTS customer;

CREATE TABLE customer (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    fid varchar(20) NOT NULL UNIQUE,
    openid varchar(255) NOT NULL,
    nickname varchar(255),
    realname varchar(255),
    telephone varchar(32),
    sex tinyint(4) DEFAULT 0,
    province varchar(255),
    city varchar(255),
    country varchar(255),
    avatar varchar(255),
    unionid varchar(255),
    created_at dateTime,
    logined_at dateTime,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

--
-- Table for Customer Reserve
--

DROP TABLE IF EXISTS customer_reserve;

CREATE TABLE customer_reserve (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    fid varchar(20) NOT NULL,
    rid varchar(32) NOT NULL UNIQUE,
    username varchar(32) NOT NULL,
    age tinyint(4),
    sex varchar(32),
    desc1 varchar(32),
    desc2 text,
    telephone varchar(32) NOT NULL,
    isft tinyint(4) NOT NULL DEFAULT 1,
    rtime dateTime,
    created_at dateTime,
    updated_at dateTime,
    status tinyint(4) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;