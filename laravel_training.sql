/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50734 (5.7.34)
 Source Host           : localhost:3306
 Source Schema         : laravel_training

 Target Server Type    : MySQL
 Target Server Version : 50734 (5.7.34)
 File Encoding         : 65001

 Date: 22/05/2023 09:49:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of brands
-- ----------------------------
BEGIN;
INSERT INTO `brands` (`id`, `category_id`, `name`) VALUES (1, 1, 'nike');
INSERT INTO `brands` (`id`, `category_id`, `name`) VALUES (2, 1, 'addidas');
INSERT INTO `brands` (`id`, `category_id`, `name`) VALUES (3, 2, 'owen');
COMMIT;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories
-- ----------------------------
BEGIN;
INSERT INTO `categories` (`id`, `name`, `image`) VALUES (1, 'Quần áo', NULL);
INSERT INTO `categories` (`id`, `name`, `image`) VALUES (2, 'Giầy thể thao', NULL);
INSERT INTO `categories` (`id`, `name`, `image`) VALUES (3, 'Điện thoại', NULL);
COMMIT;

-- ----------------------------
-- Table structure for product_tag
-- ----------------------------
DROP TABLE IF EXISTS `product_tag`;
CREATE TABLE `product_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_tag
-- ----------------------------
BEGIN;
INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`) VALUES (1, 1, 1);
INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`) VALUES (2, 1, 2);
INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`) VALUES (3, 2, 2);
COMMIT;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
BEGIN;
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (1, 'quần áo 1', NULL, 100, NULL, 1);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (2, 'quần áo 2', NULL, 100, NULL, 1);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (3, 'Giầy thể thao 1', NULL, 100, NULL, 2);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (4, 'Giầy thể thao 2', NULL, 100, NULL, 2);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (5, 'Điện thoại 1', NULL, 100, NULL, 3);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (6, 'Điện thoại 2', NULL, 100, NULL, 3);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (7, 'áo bóng đá', 'áo', 200000, NULL, NULL);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (8, 'áo bóng đá', 'áo', 200000, NULL, NULL);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (9, 'quần đùi', 'quần', 1000000, NULL, NULL);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (21, 'reno pro1', 'test', 6000000, 'images/oppo-reno8z-5g-den.jpg', NULL);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES (22, 'sp 5', 'test', 1000000, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tags
-- ----------------------------
BEGIN;
INSERT INTO `tags` (`id`, `name`) VALUES (1, 'thoi trang');
INSERT INTO `tags` (`id`, `name`) VALUES (2, 'the thao');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
