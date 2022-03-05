SET FOREIGN_KEY_CHECKS=0;
INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES
    (null,'Категория-6',1, NOW(),NOW());

SET @last_id = LAST_INSERT_ID();
INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES
    (@last_id,'ПодКатегория-6-1',1,NOW(),NOW());

SET @last_sub_id = LAST_INSERT_ID();
INSERT INTO product (`category_id`,`name`,`description`,`created`,`updated`,`price`,`active`) VALUES
    (@last_sub_id,
    'Продукт6-1_1',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    NOW(),NOW(),150,1);

SET @last_product_id = LAST_INSERT_ID();
INSERT INTO comment (`product_id`,`username`,`text`,`rating`,`created`) VALUES
    (@last_product_id,'TestUser Abddg',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW()),
    (@last_product_id,'TestUser Gghff',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    1,NOW()),
    (@last_product_id,'TestUser Bffsf',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    3,NOW()),
    (@last_product_id,'TestUser Ysdsd',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW());

INSERT INTO product (`category_id`,`name`,`description`,`created`,`updated`,`price`,`active`) VALUES
    (@last_sub_id,'Продукт6-1_2',"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo, neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.",
    NOW(),NOW(),251,1);

SET @last_product_id = LAST_INSERT_ID();
INSERT INTO comment (`product_id`,`username`,`text`,`rating`,`created`) VALUES
    (@last_product_id,'TestUser Abddg',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    3,NOW()),
    (@last_product_id,'TestUser Gghff',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    2,NOW()),
    (@last_product_id,'TestUser Bffsf',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    4,NOW()),
    (@last_product_id,'TestUser Ysdsd',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW());

INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES    
    (@last_id,'ПодКатегория-6-2',1,NOW(),NOW());

SET @last_sub_id = LAST_INSERT_ID();
INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES
    (@last_sub_id,'ПодКатегория-6-2-1',1,NOW(),NOW());

SET @last_sub_cat_id = LAST_INSERT_ID();
INSERT INTO product (`category_id`,`name`,`description`,`created`,`updated`,`price`,`active`) VALUES
    (@last_sub_cat_id,'Продукт6-2-1_1',"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo, neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.",
    NOW(),NOW(),200,1);

SET @last_product_id = LAST_INSERT_ID();
INSERT INTO comment (`product_id`,`username`,`text`,`rating`,`created`) VALUES
    (@last_product_id,'TestUser Abddg',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    1,NOW()),
    (@last_product_id,'TestUser Gghff',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    4,NOW()),
    (@last_product_id,'TestUser Bffsf',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW()),
    (@last_product_id,'TestUser Ysdsd',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW());

INSERT INTO product (`category_id`,`name`,`description`,`created`,`updated`,`price`,`active`) VALUES
    (@last_sub_cat_id,'Продукт6-2-1_2',"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo, neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.",
    NOW(),NOW(),186,1);

SET @last_product_id = LAST_INSERT_ID();
INSERT INTO comment (`product_id`,`username`,`text`,`rating`,`created`) VALUES
    (@last_product_id,'TestUser Abddg',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    4,NOW()),
    (@last_product_id,'TestUser Gghff',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    2,NOW()),
    (@last_product_id,'TestUser Bffsf',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW()),
    (@last_product_id,'TestUser Ysdsd',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    1,NOW());

INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES    
    (@last_sub_id,'ПодКатегория-6-2-2',1,NOW(),NOW());

SET @last_sub_cat_id = LAST_INSERT_ID();
INSERT INTO product (`category_id`,`name`,`description`,`created`,`updated`,`price`,`active`) VALUES
    (@last_sub_cat_id,'Продукт6-2-2_1',"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo, neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.",
    NOW(),NOW(),185,1);

INSERT INTO product (`category_id`,`name`,`description`,`created`,`updated`,`price`,`active`) VALUES
    (@last_sub_cat_id,'Продукт6-2-2_2',"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo, neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.",
    NOW(),NOW(),122,1);

SET @last_product_id = LAST_INSERT_ID();
INSERT INTO comment (`product_id`,`username`,`text`,`rating`,`created`) VALUES
    (@last_product_id,'TestUser Abddg',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW()),
    (@last_product_id,'TestUser Gghff',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    5,NOW()),
    (@last_product_id,'TestUser Bffsf',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    3,NOW()),
    (@last_product_id,'TestUser Ysdsd',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo,neque consectetur congue congue, neque arcu suscipit metus, quis imperdiet.',
    1,NOW());

INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES
    (null,'Категория-7',1, NOW(),NOW());

SET @last_id = LAST_INSERT_ID();
INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES
    (@last_id,'ПодКатегория-7-1',1,NOW(),NOW());

INSERT INTO category (`parent_id`, `name`, `active`,`created`,`updated`) VALUES    
    (@last_id,'ПодКатегория-7-2',1,NOW(),NOW());

SET FOREIGN_KEY_CHECKS=1;