
INSERT INTO "tree_ternary" VALUES (1, 0, '2016-6-29 10:52:55.34856', 'M', 0, 2, 3, 4, 1, '1');
INSERT INTO "tree_ternary" VALUES (2, 1, '2016-6-29 16:51:37.375614', 'L', 1, 5, 6, 7, 1, '2,1');
INSERT INTO "tree_ternary" VALUES (3, 1, '2016-6-29 16:51:48.115372', 'M', 1, 8, 9, 10, 2, '3,1');
INSERT INTO "tree_ternary" VALUES (4, 1, '2016-6-29 16:52:00.36259', 'R', 1, 11, 12, 13, 3, '4,1');
INSERT INTO "tree_ternary" VALUES (5, 2, '2016-6-29 17:02:55.793637', 'L', 2, 15, 19, 21, 1, '5,2,1');
INSERT INTO "tree_ternary" VALUES (6, 2, '2016-6-29 17:03:22.698471', 'M', 2, 22, 24, 27, 2, '6,2,1');
INSERT INTO "tree_ternary" VALUES (7, 2, '2016-6-29 17:03:36.862366', 'R', 2, 29, 30, 31, 3, '7,2,1');
INSERT INTO "tree_ternary" VALUES (8, 3, '2016-6-29 17:04:00.172203', 'L', 2, 32, 8, 8, 4, '8,3,1');
INSERT INTO "tree_ternary" VALUES (9, 3, '2016-6-29 17:04:02.652034', 'M', 2, 9, 9, 9, 5, '9,3,1');
INSERT INTO "tree_ternary" VALUES (10, 3, '2016-6-29 17:04:04.442127', 'R', 2, 10, 10, 10, 6, '10,3,1');
INSERT INTO "tree_ternary" VALUES (11, 4, '2016-6-29 17:04:28.567215', 'L', 2, 11, 11, 11, 7, '11,4,1');
INSERT INTO "tree_ternary" VALUES (12, 4, '2016-6-29 17:04:30.431371', 'M', 2, 12, 12, 12, 8, '12,4,1');
INSERT INTO "tree_ternary" VALUES (13, 4, '2016-6-29 17:04:38.487529', 'R', 2, 13, 13, 13, 9, '13,4,1');
INSERT INTO "tree_ternary" VALUES (15, 5, '2016-6-29 17:08:34.759935', 'L', 3, 15, 15, 15, 1, '15,5,2,1');
INSERT INTO "tree_ternary" VALUES (19, 5, '2016-6-29 17:08:39.799538', 'M', 3, 19, 19, 19, 2, '19,5,2,1');
INSERT INTO "tree_ternary" VALUES (21, 5, '2016-6-29 17:08:42.835523', 'R', 3, 21, 21, 21, 3, '21,5,2,1');
INSERT INTO "tree_ternary" VALUES (22, 6, '2016-6-29 17:08:45.2092', 'L', 3, 22, 22, 22, 4, '22,6,2,1');
INSERT INTO "tree_ternary" VALUES (24, 6, '2016-6-29 17:08:47.06522', 'M', 3, 24, 24, 24, 5, '24,6,2,1');
INSERT INTO "tree_ternary" VALUES (27, 6, '2016-6-29 17:08:49.299359', 'R', 3, 27, 27, 27, 6, '27,6,2,1');
INSERT INTO "tree_ternary" VALUES (29, 7, '2016-6-29 17:08:52.330385', 'L', 3, 29, 29, 29, 7, '29,7,2,1');
INSERT INTO "tree_ternary" VALUES (30, 7, '2016-6-29 17:09:02.918276', 'M', 3, 30, 30, 30, 8, '30,7,2,1');
INSERT INTO "tree_ternary" VALUES (31, 7, '2016-6-29 17:09:05.83871', 'R', 3, 31, 31, 31, 9, '31,7,2,1');
INSERT INTO "tree_ternary" VALUES (32, 8, '2016-6-29 17:53:58.159849', 'L', 3, 32, 32, 32, 10, '32,8,3,1');


/*
BEGIN TRANSACTION;
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (0, 1, 'M', 0, 2, 3, 4, 1, '1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (1, 2, 'L', 1, 5, 6, 7, 1, '2,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (1, 3, 'M', 1, 8, 9, 3, 2, '3,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (1, 4, 'R', 1, 10, 4, 4, 3, '4,1');

INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (2, 5, 'L', 2, 5, 5, 5, 1, '5,2,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (2, 6, 'M', 2, 6, 6, 6, 2, '6,2,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (2, 7, 'R', 2, 7, 7, 7, 3, '7,2,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (3, 8, 'L', 2, 8, 8, 8, 4, '8,3,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (3, 9, 'M', 2, 9, 9, 9, 5, '9,3,1');
INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root) VALUES (4, 10, 'L', 2, 10, 10, 10, 6, '10,4,1');
COMMIT;

*/
