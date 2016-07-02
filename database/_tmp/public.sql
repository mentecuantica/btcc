/*
Navicat PGSQL Data Transfer

Source Server         : vagrant-pgsql
Source Server Version : 90407
Source Host           : localhost:54320
Source Database       : btcc
Source Schema         : public

Target Server Type    : PGSQL
Target Server Version : 90407
File Encoding         : 65001

Date: 2016-06-10 18:43:57
*/


-- ----------------------------
-- Sequence structure for activations_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."activations_id_seq";
CREATE SEQUENCE "public"."activations_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 2
 CACHE 1;
SELECT setval('"public"."activations_id_seq"', 2, true);

-- ----------------------------
-- Sequence structure for binary_tree_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."binary_tree_id_seq";
CREATE SEQUENCE "public"."binary_tree_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 15
 CACHE 1;
SELECT setval('"public"."binary_tree_id_seq"', 15, true);

-- ----------------------------
-- Sequence structure for linear_tree_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."linear_tree_id_seq";
CREATE SEQUENCE "public"."linear_tree_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 20
 CACHE 1;
SELECT setval('"public"."linear_tree_id_seq"', 20, true);

-- ----------------------------
-- Sequence structure for packages_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."packages_id_seq";
CREATE SEQUENCE "public"."packages_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."packages_id_seq"', 1, true);

-- ----------------------------
-- Sequence structure for persistences_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."persistences_id_seq";
CREATE SEQUENCE "public"."persistences_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 26
 CACHE 1;
SELECT setval('"public"."persistences_id_seq"', 26, true);

-- ----------------------------
-- Sequence structure for profiles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."profiles_id_seq";
CREATE SEQUENCE "public"."profiles_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 6
 CACHE 1;
SELECT setval('"public"."profiles_id_seq"', 6, true);

-- ----------------------------
-- Sequence structure for reminders_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."reminders_id_seq";
CREATE SEQUENCE "public"."reminders_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for roles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."roles_id_seq";
CREATE SEQUENCE "public"."roles_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for throttle_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."throttle_id_seq";
CREATE SEQUENCE "public"."throttle_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for user_invites_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."user_invites_id_seq";
CREATE SEQUENCE "public"."user_invites_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for users_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."users_id_seq";
CREATE SEQUENCE "public"."users_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 17
 CACHE 1;
SELECT setval('"public"."users_id_seq"', 17, true);

-- ----------------------------
-- Sequence structure for users_transactions_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."users_transactions_id_seq";
CREATE SEQUENCE "public"."users_transactions_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for users_wallets_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."users_wallets_id_seq";
CREATE SEQUENCE "public"."users_wallets_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Table structure for activations
-- ----------------------------
DROP TABLE IF EXISTS "public"."activations";
CREATE TABLE "public"."activations" (
"id" int4 DEFAULT nextval('activations_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"code" varchar(255) COLLATE "default" NOT NULL,
"completed" bool DEFAULT false NOT NULL,
"completed_at" timestamp(0),
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of activations
-- ----------------------------
INSERT INTO "public"."activations" VALUES ('1', '1', 'Ks4viyTPiOWU1XXL7HG8QOTYdTMButnm', 't', '2016-06-06 23:36:59', '2016-06-06 23:36:59', '2016-06-06 23:36:59');
INSERT INTO "public"."activations" VALUES ('2', '3', 'lGgIFcmoYzFyRMNMZiE1J3cF5tSdxVVJ', 't', '2016-06-07 00:56:21', '2016-06-07 00:56:21', '2016-06-07 00:56:21');

-- ----------------------------
-- Table structure for binary_tree
-- ----------------------------
DROP TABLE IF EXISTS "public"."binary_tree";
CREATE TABLE "public"."binary_tree" (
"id" int4 DEFAULT nextval('binary_tree_id_seq'::regclass) NOT NULL,
"parent_id" int4 NOT NULL,
"child_id" int4 NOT NULL,
"bt_position" "public"."e_binary_position" NOT NULL,
"l_child_id" int4,
"r_child_id" int4,
"refer_id" int4,
"bt_level" int4,
"info" text COLLATE "default"
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of binary_tree
-- ----------------------------
INSERT INTO "public"."binary_tree" VALUES ('1', '0', '1', 'N', null, null, null, '0', null);
INSERT INTO "public"."binary_tree" VALUES ('2', '1', '2', 'L', null, null, null, '1', null);
INSERT INTO "public"."binary_tree" VALUES ('3', '1', '3', 'R', null, null, null, '1', null);
INSERT INTO "public"."binary_tree" VALUES ('4', '2', '4', 'L', null, null, null, '2', null);
INSERT INTO "public"."binary_tree" VALUES ('13', '3', '14', 'L', null, null, null, null, null);
INSERT INTO "public"."binary_tree" VALUES ('12', '4', '13', 'R', null, null, null, null, null);
INSERT INTO "public"."binary_tree" VALUES ('14', '4', '15', 'L', null, null, null, null, null);
INSERT INTO "public"."binary_tree" VALUES ('15', '14', '17', 'R', null, null, null, null, null);

-- ----------------------------
-- Table structure for binary_tree_copy
-- ----------------------------
DROP TABLE IF EXISTS "public"."binary_tree_copy";
CREATE TABLE "public"."binary_tree_copy" (
"id" int4 DEFAULT nextval('binary_tree_id_seq'::regclass) NOT NULL,
"parent_id" int4 NOT NULL,
"child_id" int4 NOT NULL,
"bt_position" "public"."e_binary_position" NOT NULL,
"l_child_id" int4,
"r_child_id" int4,
"refer_id" int4,
"bt_level" int4,
"info" text COLLATE "default"
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of binary_tree_copy
-- ----------------------------
INSERT INTO "public"."binary_tree_copy" VALUES ('1', '0', '1', 'N', null, null, null, '0', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('2', '1', '2', 'L', null, null, null, '1', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('3', '1', '3', 'R', null, null, null, '1', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('4', '2', '4', 'L', null, null, null, '2', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('5', '2', '5', 'R', null, null, null, '2', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('6', '3', '6', 'R', null, null, null, '2', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('10', '3', '10', 'L', null, null, null, '2', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('7', '4', '7', 'L', null, null, null, '3', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('8', '5', '8', 'L', null, null, null, '3', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('9', '5', '9', 'R', null, null, null, '3', null);
INSERT INTO "public"."binary_tree_copy" VALUES ('11', '10', '42', 'L', null, null, null, null, null);

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS "public"."cache";
CREATE TABLE "public"."cache" (
"key" varchar(255) COLLATE "default" NOT NULL,
"value" text COLLATE "default" NOT NULL,
"expiration" int4 NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for linear_tree
-- ----------------------------
DROP TABLE IF EXISTS "public"."linear_tree";
CREATE TABLE "public"."linear_tree" (
"id" int4 DEFAULT nextval('linear_tree_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"parent_id" int4,
"lft" int4,
"rgt" int4,
"depth" int4,
"comment" varchar(255) COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of linear_tree
-- ----------------------------
INSERT INTO "public"."linear_tree" VALUES ('1', '1', null, '1', '14', '0', null, '2016-06-06 23:41:14', '2016-06-09 20:38:08');
INSERT INTO "public"."linear_tree" VALUES ('3', '2', '1', '2', '3', '1', null, '2016-06-07 00:11:49', '2016-06-07 00:12:14');
INSERT INTO "public"."linear_tree" VALUES ('16', '13', '1', '4', '5', '1', null, '2016-06-07 11:42:18', '2016-06-07 11:42:18');
INSERT INTO "public"."linear_tree" VALUES ('17', '14', '1', '6', '7', '1', null, '2016-06-09 19:58:37', '2016-06-09 19:58:37');
INSERT INTO "public"."linear_tree" VALUES ('18', '15', '1', '8', '9', '1', null, '2016-06-09 20:02:14', '2016-06-09 20:02:14');
INSERT INTO "public"."linear_tree" VALUES ('19', '16', '1', '10', '11', '1', null, '2016-06-09 20:26:47', '2016-06-09 20:26:47');
INSERT INTO "public"."linear_tree" VALUES ('20', '17', '1', '12', '13', '1', null, '2016-06-09 20:38:08', '2016-06-09 20:38:08');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS "public"."migrations";
CREATE TABLE "public"."migrations" (
"migration" varchar(255) COLLATE "default" NOT NULL,
"batch" int4 NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO "public"."migrations" VALUES ('2016_06_01_090147_migration_cartalyst_sentinel', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_103027_create_users_wallets_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_105546_create_cache_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_155305_create_packages_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_162606_create_user_invites_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_195846_create_sessions_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_202654_create_profiles_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_01_224213_create_users_transactions_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_06_03_233348_create_tree_linears_table', '1');

-- ----------------------------
-- Table structure for packages
-- ----------------------------
DROP TABLE IF EXISTS "public"."packages";
CREATE TABLE "public"."packages" (
"id" int4 DEFAULT nextval('packages_id_seq'::regclass) NOT NULL,
"name" varchar(100) COLLATE "default" NOT NULL,
"start_balance" int4 NOT NULL,
"description" text COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of packages
-- ----------------------------
INSERT INTO "public"."packages" VALUES ('1', 'ONE', '100', 'Onepack', null, null);
INSERT INTO "public"."packages" VALUES ('2', 'TWO', '200', 'Twopack', null, null);
INSERT INTO "public"."packages" VALUES ('3', 'THREE', '300', 'Threepack', null, null);

-- ----------------------------
-- Table structure for persistences
-- ----------------------------
DROP TABLE IF EXISTS "public"."persistences";
CREATE TABLE "public"."persistences" (
"id" int4 DEFAULT nextval('persistences_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"code" varchar(255) COLLATE "default" NOT NULL,
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of persistences
-- ----------------------------
INSERT INTO "public"."persistences" VALUES ('1', '1', 'xD8M49LfGrhYRXVMqRC6x9bIQjNUfqet', '2016-06-07 08:47:06', '2016-06-07 08:47:06');
INSERT INTO "public"."persistences" VALUES ('2', '1', 'AevsUq7nSK8py5wrgB5Xe9VORDF5wqCK', '2016-06-07 08:57:20', '2016-06-07 08:57:20');
INSERT INTO "public"."persistences" VALUES ('11', '1', 'OFvi4Lsmx54fxWbRDpPM9tCSo3YfDEF7', '2016-06-07 13:55:18', '2016-06-07 13:55:18');
INSERT INTO "public"."persistences" VALUES ('12', '1', 'wufVO7TTf0L6cPiwa37DtWZToAPbVPO1', '2016-06-07 14:03:01', '2016-06-07 14:03:01');
INSERT INTO "public"."persistences" VALUES ('13', '1', 'phutG15agPAUzE8qixxx2QOnyaIYOkWO', '2016-06-07 14:12:14', '2016-06-07 14:12:14');
INSERT INTO "public"."persistences" VALUES ('14', '1', 'bIKg50PvCgwHOzTAo7M9Ydr8IkFxWj42', '2016-06-07 14:12:21', '2016-06-07 14:12:21');
INSERT INTO "public"."persistences" VALUES ('15', '1', 'nlZ0tQVmB82mx0ialO0OeAvRcYN2V1Lb', '2016-06-07 14:12:35', '2016-06-07 14:12:35');
INSERT INTO "public"."persistences" VALUES ('16', '1', 'MxqF4sT62OCGAnrwCv7zYSDKWZyABGbQ', '2016-06-07 14:14:22', '2016-06-07 14:14:22');
INSERT INTO "public"."persistences" VALUES ('17', '1', 'qd2COu6XMJRF97Q3VSIC3IgsD6UEQM4s', '2016-06-07 14:16:51', '2016-06-07 14:16:51');
INSERT INTO "public"."persistences" VALUES ('18', '1', 'M4IrqpUfOv2ktlxcBe8NkDZrfUwXG5rO', '2016-06-07 14:19:25', '2016-06-07 14:19:25');
INSERT INTO "public"."persistences" VALUES ('19', '1', '7MU8jysZ0KEHd0sxpxDjOfndhjuFHYlK', '2016-06-07 14:19:35', '2016-06-07 14:19:35');
INSERT INTO "public"."persistences" VALUES ('20', '1', 'xYCL1Bcd85iCc0JKsoTa0KwuAfttVY2c', '2016-06-07 14:21:54', '2016-06-07 14:21:54');
INSERT INTO "public"."persistences" VALUES ('22', '1', 'z5YDW4elrrmrdFlofAN6lkFF497sSHEk', '2016-06-07 16:54:41', '2016-06-07 16:54:41');
INSERT INTO "public"."persistences" VALUES ('23', '1', 'vg90yIwPgp94s8C3uenuXzNJcTtB43VR', '2016-06-08 12:01:35', '2016-06-08 12:01:35');
INSERT INTO "public"."persistences" VALUES ('25', '1', 'qv4nMBylwQmRMdk4scWnR5uy29Bb35SF', '2016-06-09 20:33:43', '2016-06-09 20:33:43');
INSERT INTO "public"."persistences" VALUES ('26', '1', 'D9uChphSXunxA6LDGo0iv9ucrsZzShkd', '2016-06-10 11:16:34', '2016-06-10 11:16:34');

-- ----------------------------
-- Table structure for profiles
-- ----------------------------
DROP TABLE IF EXISTS "public"."profiles";
CREATE TABLE "public"."profiles" (
"id" int4 DEFAULT nextval('profiles_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"name" varchar(255) COLLATE "default",
"surname" varchar(255) COLLATE "default",
"country" varchar(255) COLLATE "default",
"city" varchar(255) COLLATE "default",
"additional" json,
"created_at" timestamp(0),
"updated_at" timestamp(0),
"package_id" int4 NOT NULL,
"phone" varchar(20) COLLATE "default",
"sponsor_id" int8,
"country_id" int4
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of profiles
-- ----------------------------
INSERT INTO "public"."profiles" VALUES ('3', '13', null, null, null, null, null, '2016-06-07 11:42:18', '2016-06-07 11:42:18', '2', '9222222222', null, null);
INSERT INTO "public"."profiles" VALUES ('4', '15', null, null, 'Netherlands', null, null, '2016-06-09 20:02:14', '2016-06-09 20:02:14', '2', null, null, null);
INSERT INTO "public"."profiles" VALUES ('5', '16', null, null, 'Netherlands', null, null, '2016-06-09 20:26:47', '2016-06-09 20:26:47', '2', null, null, null);
INSERT INTO "public"."profiles" VALUES ('6', '17', null, null, 'Netherlands', null, null, '2016-06-09 20:38:08', '2016-06-09 20:38:08', '3', null, null, null);

-- ----------------------------
-- Table structure for reminders
-- ----------------------------
DROP TABLE IF EXISTS "public"."reminders";
CREATE TABLE "public"."reminders" (
"id" int4 DEFAULT nextval('reminders_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"code" varchar(255) COLLATE "default" NOT NULL,
"completed" bool DEFAULT false NOT NULL,
"completed_at" timestamp(0),
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of reminders
-- ----------------------------

-- ----------------------------
-- Table structure for role_users
-- ----------------------------
DROP TABLE IF EXISTS "public"."role_users";
CREATE TABLE "public"."role_users" (
"user_id" int4 NOT NULL,
"role_id" int4 NOT NULL,
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of role_users
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS "public"."roles";
CREATE TABLE "public"."roles" (
"id" int4 DEFAULT nextval('roles_id_seq'::regclass) NOT NULL,
"slug" varchar(255) COLLATE "default" NOT NULL,
"name" varchar(255) COLLATE "default" NOT NULL,
"permissions" text COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of roles
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS "public"."sessions";
CREATE TABLE "public"."sessions" (
"id" varchar(255) COLLATE "default" NOT NULL,
"user_id" int4,
"ip_address" varchar(45) COLLATE "default",
"user_agent" text COLLATE "default",
"payload" text COLLATE "default" NOT NULL,
"last_activity" int4 NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO "public"."sessions" VALUES ('e3b5c2363420a762ad5613cefa345b9e98876e61', null, '192.168.10.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNndxTmlQUzl5Um5oWTVBMU9INW4zcnBCajc0MWZ4YUtmRjlxdTc1QiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9idGNjLnZndC9wYXJ0bmVyL2NyZWF0ZSI7fXM6NToiZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxODoiY2FydGFseXN0X3NlbnRpbmVsIjtzOjMyOiJEOXVDaHBoU1h1bnhBNkxER28waXY5dWNyc1p6U2hrZCI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfc2YyX21ldGEiO2E6Mzp7czoxOiJ1IjtpOjE0NjU1NjUwMzQ7czoxOiJjIjtpOjE0NjU1NTYwMTU7czoxOiJsIjtzOjE6IjAiO319', '1465565034');

-- ----------------------------
-- Table structure for throttle
-- ----------------------------
DROP TABLE IF EXISTS "public"."throttle";
CREATE TABLE "public"."throttle" (
"id" int4 DEFAULT nextval('throttle_id_seq'::regclass) NOT NULL,
"user_id" int4,
"type" varchar(255) COLLATE "default" NOT NULL,
"ip" varchar(255) COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of throttle
-- ----------------------------

-- ----------------------------
-- Table structure for user_invites
-- ----------------------------
DROP TABLE IF EXISTS "public"."user_invites";
CREATE TABLE "public"."user_invites" (
"id" int4 DEFAULT nextval('user_invites_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"package_id" int4 NOT NULL,
"email" varchar(255) COLLATE "default" NOT NULL,
"type" int4 NOT NULL,
"status" int4 NOT NULL,
"sender" int4,
"comment" varchar(255) COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of user_invites
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
"id" int4 DEFAULT nextval('users_id_seq'::regclass) NOT NULL,
"email" varchar(255) COLLATE "default" NOT NULL,
"password" varchar(255) COLLATE "default" NOT NULL,
"permissions" text COLLATE "default",
"last_login" timestamp(0),
"first_name" varchar(255) COLLATE "default",
"last_name" varchar(255) COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0),
"parent_id" int4
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO "public"."users" VALUES ('1', 'top@btcc.vgt', '$2y$10$DzAQzojYjyyyCFx5TQQma.rRYOZCaYQ1ZXquslZCIlhofzyCZJFRO', null, '2016-06-10 11:16:34', 'Top', 'Usario', '2016-06-06 23:36:59', '2016-06-10 11:16:34', null);
INSERT INTO "public"."users" VALUES ('2', 'first@btcc.vgt', '$2y$10$Q5rAiEE2bzbIwKybuUkXFOwfdQjy81x6AVCvKxGUXpDErf6bzdoZS', null, null, 'Mike', null, '2016-06-07 00:06:14', '2016-06-07 00:06:14', null);
INSERT INTO "public"."users" VALUES ('3', 'second@btcc.vgt', '$2y$10$PUmnpdID/ClM2lKehoOApejL7PDhFolj3OrpXoBePiTMaMicv4w9K', null, null, 'Mike', 'Fiddler', '2016-06-07 00:56:21', '2016-06-07 00:56:21', null);
INSERT INTO "public"."users" VALUES ('4', 'fouth@bt.ru', '24242432', null, '2016-06-08 17:26:07', 'Four', 'User', '2016-06-08 17:25:52', '2016-06-08 17:25:49', null);
INSERT INTO "public"."users" VALUES ('13', 'test@repo.ru', '123456', null, null, null, null, '2016-06-07 11:42:18', '2016-06-07 11:42:18', null);
INSERT INTO "public"."users" VALUES ('14', 'yura.karas@gmail.com', '123456', null, null, null, null, '2016-06-09 19:58:37', '2016-06-09 19:58:37', null);
INSERT INTO "public"."users" VALUES ('15', 'fuck@sideways.ru', '123456', null, null, null, null, '2016-06-09 20:02:14', '2016-06-09 20:02:14', null);
INSERT INTO "public"."users" VALUES ('16', '6sixty1one@gmail.com', '$2y$10$NgehdPaglR5o8s1TF4.jq.iy6JJQO0.mrw3zAm7w4Y7A.19H7tcrW', null, null, null, null, '2016-06-09 20:26:47', '2016-06-09 20:26:47', null);
INSERT INTO "public"."users" VALUES ('17', 'fuck@sidewayssss.ru', '$2y$10$LHt5utaYOz4C35uMTDMcQuk4XX/YPWdRwfu.UQsQrcPUfLsjPB/9e', null, null, null, null, '2016-06-09 20:38:08', '2016-06-09 20:38:08', null);

-- ----------------------------
-- Table structure for users_transactions
-- ----------------------------
DROP TABLE IF EXISTS "public"."users_transactions";
CREATE TABLE "public"."users_transactions" (
"id" int4 DEFAULT nextval('users_transactions_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"amount" int4 NOT NULL,
"type" int4 NOT NULL,
"status" int4 NOT NULL,
"sender" int4 NOT NULL,
"reciever" int4 NOT NULL,
"debit_flag" bool NOT NULL,
"credit_flag" bool NOT NULL,
"comment" varchar(255) COLLATE "default",
"hash" varchar(255) COLLATE "default",
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of users_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for users_wallets
-- ----------------------------
DROP TABLE IF EXISTS "public"."users_wallets";
CREATE TABLE "public"."users_wallets" (
"id" int4 DEFAULT nextval('users_wallets_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"type" int2 NOT NULL,
"comment" varchar(255) COLLATE "default" NOT NULL,
"created_at" timestamp(0),
"updated_at" timestamp(0)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of users_wallets
-- ----------------------------

-- ----------------------------
-- Alter Sequences Owned By 
-- ----------------------------
ALTER SEQUENCE "public"."activations_id_seq" OWNED BY "activations"."id";
ALTER SEQUENCE "public"."binary_tree_id_seq" OWNED BY "binary_tree"."id";
ALTER SEQUENCE "public"."linear_tree_id_seq" OWNED BY "linear_tree"."id";
ALTER SEQUENCE "public"."packages_id_seq" OWNED BY "packages"."id";
ALTER SEQUENCE "public"."persistences_id_seq" OWNED BY "persistences"."id";
ALTER SEQUENCE "public"."profiles_id_seq" OWNED BY "profiles"."id";
ALTER SEQUENCE "public"."reminders_id_seq" OWNED BY "reminders"."id";
ALTER SEQUENCE "public"."roles_id_seq" OWNED BY "roles"."id";
ALTER SEQUENCE "public"."throttle_id_seq" OWNED BY "throttle"."id";
ALTER SEQUENCE "public"."user_invites_id_seq" OWNED BY "user_invites"."id";
ALTER SEQUENCE "public"."users_id_seq" OWNED BY "users"."id";
ALTER SEQUENCE "public"."users_transactions_id_seq" OWNED BY "users_transactions"."id";
ALTER SEQUENCE "public"."users_wallets_id_seq" OWNED BY "users_wallets"."id";

-- ----------------------------
-- Primary Key structure for table activations
-- ----------------------------
ALTER TABLE "public"."activations" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table binary_tree
-- ----------------------------
CREATE INDEX "idx_parent_id" ON "public"."binary_tree" USING btree ("parent_id");
CREATE INDEX "idx_parent_child" ON "public"."binary_tree" USING btree ("parent_id", "child_id");

-- ----------------------------
-- Uniques structure for table binary_tree
-- ----------------------------
ALTER TABLE "public"."binary_tree" ADD UNIQUE ("parent_id", "child_id", "bt_position");

-- ----------------------------
-- Primary Key structure for table binary_tree
-- ----------------------------
ALTER TABLE "public"."binary_tree" ADD PRIMARY KEY ("parent_id", "child_id");

-- ----------------------------
-- Indexes structure for table binary_tree_copy
-- ----------------------------
CREATE INDEX "idx_parent_id_copy" ON "public"."binary_tree_copy" USING btree ("parent_id");
CREATE INDEX "idx_parent_child_copy" ON "public"."binary_tree_copy" USING btree ("parent_id", "child_id");

-- ----------------------------
-- Uniques structure for table binary_tree_copy
-- ----------------------------
ALTER TABLE "public"."binary_tree_copy" ADD UNIQUE ("parent_id", "child_id", "bt_position");

-- ----------------------------
-- Primary Key structure for table binary_tree_copy
-- ----------------------------
ALTER TABLE "public"."binary_tree_copy" ADD PRIMARY KEY ("parent_id", "child_id");

-- ----------------------------
-- Uniques structure for table cache
-- ----------------------------
ALTER TABLE "public"."cache" ADD UNIQUE ("key");

-- ----------------------------
-- Indexes structure for table linear_tree
-- ----------------------------
CREATE INDEX "linear_tree_user_id_index" ON "public"."linear_tree" USING btree ("user_id");
CREATE INDEX "linear_tree_parent_id_index" ON "public"."linear_tree" USING btree ("parent_id");
CREATE INDEX "linear_tree_lft_index" ON "public"."linear_tree" USING btree ("lft");
CREATE INDEX "linear_tree_rgt_index" ON "public"."linear_tree" USING btree ("rgt");

-- ----------------------------
-- Primary Key structure for table linear_tree
-- ----------------------------
ALTER TABLE "public"."linear_tree" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table packages
-- ----------------------------
ALTER TABLE "public"."packages" ADD UNIQUE ("name");

-- ----------------------------
-- Primary Key structure for table packages
-- ----------------------------
ALTER TABLE "public"."packages" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table persistences
-- ----------------------------
ALTER TABLE "public"."persistences" ADD UNIQUE ("code");

-- ----------------------------
-- Primary Key structure for table persistences
-- ----------------------------
ALTER TABLE "public"."persistences" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table profiles
-- ----------------------------
CREATE INDEX "profiles_user_id_index" ON "public"."profiles" USING btree ("user_id");

-- ----------------------------
-- Primary Key structure for table profiles
-- ----------------------------
ALTER TABLE "public"."profiles" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table reminders
-- ----------------------------
ALTER TABLE "public"."reminders" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table role_users
-- ----------------------------
ALTER TABLE "public"."role_users" ADD PRIMARY KEY ("user_id", "role_id");

-- ----------------------------
-- Uniques structure for table roles
-- ----------------------------
ALTER TABLE "public"."roles" ADD UNIQUE ("slug");

-- ----------------------------
-- Primary Key structure for table roles
-- ----------------------------
ALTER TABLE "public"."roles" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table sessions
-- ----------------------------
ALTER TABLE "public"."sessions" ADD UNIQUE ("id");

-- ----------------------------
-- Indexes structure for table throttle
-- ----------------------------
CREATE INDEX "throttle_user_id_index" ON "public"."throttle" USING btree ("user_id");

-- ----------------------------
-- Primary Key structure for table throttle
-- ----------------------------
ALTER TABLE "public"."throttle" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table user_invites
-- ----------------------------
ALTER TABLE "public"."user_invites" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD UNIQUE ("email");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table users_transactions
-- ----------------------------
ALTER TABLE "public"."users_transactions" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table users_wallets
-- ----------------------------
CREATE INDEX "users_wallets_user_id_index" ON "public"."users_wallets" USING btree ("user_id");

-- ----------------------------
-- Primary Key structure for table users_wallets
-- ----------------------------
ALTER TABLE "public"."users_wallets" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Key structure for table "public"."user_invites"
-- ----------------------------
ALTER TABLE "public"."user_invites" ADD FOREIGN KEY ("package_id") REFERENCES "public"."packages" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."user_invites" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Key structure for table "public"."users_transactions"
-- ----------------------------
ALTER TABLE "public"."users_transactions" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
