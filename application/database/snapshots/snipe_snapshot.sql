-- MySQL dump 10.17  Distrib 10.3.25-MariaDB, for Linux (x86_64)
--
-- Host: mysql    Database: forge
-- ------------------------------------------------------
-- Server version	5.7.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `action_events`
--

DROP TABLE IF EXISTS `action_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actionable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actionable_id` bigint(20) unsigned NOT NULL,
  `target_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `fields` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `exception` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `original` text COLLATE utf8mb4_unicode_ci,
  `changes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `action_events_actionable_type_actionable_id_index` (`actionable_type`,`actionable_id`),
  KEY `action_events_batch_id_model_type_model_id_index` (`batch_id`,`model_type`,`model_id`),
  KEY `action_events_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action_events`
--

LOCK TABLES `action_events` WRITE;
/*!40000 ALTER TABLE `action_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `action_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agb_user`
--

DROP TABLE IF EXISTS `agb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agb_user` (
  `agb_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`agb_id`,`user_id`),
  KEY `agb_user_user_id_foreign` (`user_id`),
  CONSTRAINT `agb_user_agb_id_foreign` FOREIGN KEY (`agb_id`) REFERENCES `agbs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agb_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agb_user`
--

LOCK TABLES `agb_user` WRITE;
/*!40000 ALTER TABLE `agb_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `agb_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agbs`
--

DROP TABLE IF EXISTS `agbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agbs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `effective_from` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agbs_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agbs`
--

LOCK TABLES `agbs` WRITE;
/*!40000 ALTER TABLE `agbs` DISABLE KEYS */;
/*!40000 ALTER TABLE `agbs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audits`
--

DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`),
  CONSTRAINT `audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audits`
--

LOCK TABLES `audits` WRITE;
/*!40000 ALTER TABLE `audits` DISABLE KEYS */;
/*!40000 ALTER TABLE `audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner_links`
--

DROP TABLE IF EXISTS `banner_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(10) unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_links`
--

LOCK TABLES `banner_links` WRITE;
/*!40000 ALTER TABLE `banner_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner_sets`
--

DROP TABLE IF EXISTS `banner_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner_sets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_sets`
--

LOCK TABLES `banner_sets` WRITE;
/*!40000 ALTER TABLE `banner_sets` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(10) unsigned NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banners_set_id_index` (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `released_at` date DEFAULT NULL,
  `pdf_created_at` timestamp NULL DEFAULT NULL,
  `mail_sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bills_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bills`
--

LOCK TABLES `bills` WRITE;
/*!40000 ALTER TABLE `bills` DISABLE KEYS */;
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_user`
--

DROP TABLE IF EXISTS `campaign_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_user` (
  `campaign_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  KEY `campaign_user_campaign_id_index` (`campaign_id`),
  KEY `campaign_user_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_user`
--

LOCK TABLES `campaign_user` WRITE;
/*!40000 ALTER TABLE `campaign_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaign_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `all_users` tinyint(1) NOT NULL DEFAULT '1',
  `is_blacklist` tinyint(1) NOT NULL DEFAULT '0',
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commission_bonuses`
--

DROP TABLE IF EXISTS `commission_bonuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commission_bonuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `contract_id` int(10) unsigned NOT NULL DEFAULT '0',
  `calculation_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `is_percentage` tinyint(1) NOT NULL DEFAULT '0',
  `is_overhead` tinyint(1) NOT NULL DEFAULT '0',
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commission_bonuses_type_id_index` (`type_id`),
  KEY `commission_bonuses_accepted_at_index` (`accepted_at`),
  KEY `commission_bonuses_contract_id_index` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commission_bonuses`
--

LOCK TABLES `commission_bonuses` WRITE;
/*!40000 ALTER TABLE `commission_bonuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `commission_bonuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commission_types`
--

DROP TABLE IF EXISTS `commission_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commission_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_project_type` tinyint(1) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commission_types`
--

LOCK TABLES `commission_types` WRITE;
/*!40000 ALTER TABLE `commission_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `commission_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` int(10) unsigned DEFAULT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT 'ID of the internal Partner user',
  `child_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `net` decimal(8,2) NOT NULL,
  `gross` decimal(8,2) NOT NULL,
  `bonus` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `fixed_amount` tinyint(1) NOT NULL DEFAULT '0',
  `note_private` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Internal memo',
  `note_public` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Shown on the Bill to the user',
  `reviewed_at` datetime DEFAULT NULL COMMENT 'markes this as "valid"',
  `reviewed_by` int(10) unsigned DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `rejected_by` int(10) unsigned DEFAULT NULL,
  `on_hold` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'don''t put this on the bill, ask next time',
  `pending` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commissions_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `commissions_bill_id_index` (`bill_id`),
  KEY `commissions_rejected_by_index` (`rejected_by`),
  KEY `commissions_on_hold_index` (`on_hold`),
  KEY `commissions_child_user_id_index` (`child_user_id`),
  KEY `commissions_user_id_index` (`user_id`),
  KEY `commissions_gross_index` (`gross`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `street` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract_template_bonus`
--

DROP TABLE IF EXISTS `contract_template_bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_template_bonus` (
  `bonus_id` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `bonus_bundle_bonus_id_bundle_id_unique` (`bonus_id`,`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract_template_bonus`
--

LOCK TABLES `contract_template_bonus` WRITE;
/*!40000 ALTER TABLE `contract_template_bonus` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract_template_bonus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract_templates`
--

DROP TABLE IF EXISTS `contract_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancellation_days` smallint(5) unsigned NOT NULL DEFAULT '0',
  `claim_years` smallint(5) unsigned NOT NULL DEFAULT '0',
  `special_agreement` text COLLATE utf8mb4_unicode_ci,
  `vat_included` tinyint(1) NOT NULL DEFAULT '0',
  `vat_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_exclusive` tinyint(1) NOT NULL DEFAULT '0',
  `allow_overhead` tinyint(1) NOT NULL DEFAULT '0',
  `company_id` int(10) unsigned NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contract_templates_type_index` (`type`),
  KEY `contract_templates_is_default_index` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract_templates`
--

LOCK TABLES `contract_templates` WRITE;
/*!40000 ALTER TABLE `contract_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned DEFAULT NULL,
  `cancellation_days` smallint(5) unsigned NOT NULL DEFAULT '0',
  `claim_years` smallint(5) unsigned NOT NULL DEFAULT '0',
  `special_agreement` text COLLATE utf8mb4_unicode_ci,
  `vat_included` tinyint(1) NOT NULL DEFAULT '0',
  `vat_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_exclusive` tinyint(1) NOT NULL DEFAULT '0',
  `allow_overhead` tinyint(1) NOT NULL DEFAULT '0',
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL COMMENT 'the user accepted the conditions',
  `pdf_generated_at` datetime DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL COMMENT 'if null, this contract is still a draft',
  `terminated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contracts_user_id_index` (`user_id`),
  KEY `contracts_template_id_index` (`template_id`),
  KEY `contracts_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts`
--

LOCK TABLES `contracts` WRITE;
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `investments`
--

DROP TABLE IF EXISTS `investments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investments` (
  `id` int(10) unsigned NOT NULL,
  `investor_id` int(10) unsigned DEFAULT NULL,
  `project_id` int(10) unsigned DEFAULT NULL,
  `amount` int(10) unsigned DEFAULT NULL,
  `interest_rate` decimal(8,2) NOT NULL DEFAULT '1.00',
  `bonus` int(10) unsigned DEFAULT NULL,
  `bonus_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `is_first_investment` tinyint(1) DEFAULT NULL,
  `acknowledged_at` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investments_investor_id_index` (`investor_id`),
  KEY `investments_project_id_index` (`project_id`),
  KEY `investments_paid_at_index` (`paid_at`),
  KEY `investments_acknowledged_at_index` (`acknowledged_at`),
  KEY `investments_cancelled_at_index` (`cancelled_at`),
  KEY `investments_id_index` (`id`),
  KEY `investments_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `investments`
--

LOCK TABLES `investments` WRITE;
/*!40000 ALTER TABLE `investments` DISABLE KEYS */;
/*!40000 ALTER TABLE `investments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `investors`
--

DROP TABLE IF EXISTS `investors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investors` (
  `id` int(10) unsigned NOT NULL,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `claim_end` date DEFAULT NULL,
  `activation_at` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `affiliated_partner_ref_link_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investors_user_id_index` (`user_id`),
  KEY `investors_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `investors`
--

LOCK TABLES `investors` WRITE;
/*!40000 ALTER TABLE `investors` DISABLE KEYS */;
/*!40000 ALTER TABLE `investors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_clicks`
--

DROP TABLE IF EXISTS `link_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_clicks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instance_id` bigint(20) unsigned NOT NULL,
  `device` enum('phone','tablet','desktop') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `investment_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_clicks_instance_id_index` (`instance_id`),
  KEY `link_clicks_device_index` (`device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_clicks`
--

LOCK TABLES `link_clicks` WRITE;
/*!40000 ALTER TABLE `link_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_instances`
--

DROP TABLE IF EXISTS `link_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_instances_link_id_index` (`link_id`),
  KEY `link_instances_user_id_index` (`user_id`),
  KEY `link_instances_hash_index` (`hash`),
  KEY `link_instances_link_type_link_id_index` (`link_type`,`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_instances`
--

LOCK TABLES `link_instances` WRITE;
/*!40000 ALTER TABLE `link_instances` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_user`
--

DROP TABLE IF EXISTS `link_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_user` (
  `link_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  KEY `link_user_link_id_index` (`link_id`),
  KEY `link_user_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_user`
--

LOCK TABLES `link_user` WRITE;
/*!40000 ALTER TABLE `link_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `url` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
INSERT INTO `links` VALUES (1,'Startseite',NULL,'https://exporo.de/#reflink','2021-11-11 14:35:39','2021-11-11 14:35:39'),(2,'Registrierungs-Landingpage',NULL,'https://p.exporo.de/registrierung/#reflink','2021-11-11 14:35:39','2021-11-11 14:35:39');
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailings`
--

DROP TABLE IF EXISTS `mailings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `text` longtext COLLATE utf8mb4_unicode_ci,
  `html` longtext COLLATE utf8mb4_unicode_ci,
  `variables` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailings`
--

LOCK TABLES `mailings` WRITE;
/*!40000 ALTER TABLE `mailings` DISABLE KEYS */;
INSERT INTO `mailings` VALUES (1,'Begrüßung für Interessenten',NULL,'Lieber Interessent,\n\neine innovative Anlageform hat sich in den letzten Jahren zu einer aufstrebenden Alternative \nim Investmentsektor entwickelt. Die Rede ist von Digitalen Immobilieninvestments.\n\nDoch was genau sind Digitale Immobilieninvestments?\n\nViele Menschen investieren mit relativ kleinen Geldsummen in unterschiedlichste Projekte. \nÜber die Masse kommt dann das Gesamtinvestitionsvolumen zusammen.\n\nMit der Exporo AG, dem deutschen Marktführer dieser Anlageform, können Sie bereits \nab einem Betrag von 500 Euro direkt in ausgewählte Immobilienprojekte investieren. \nDas Beste daran: Bei einer typischen Laufzeit von 1 - 2 Jahren erhalten \nSie eine **Verzinsung von 4 bis 6 % im Jahr**.\n\nExporo ist für Anleger kostenfrei - durch die schlanken Strukturen der Onlineabwicklung \nentsteht ein Kostenvorteil, welcher zu dem geringen Mindestinvestment und zu attraktiven Renditen führt.\n\nHier können Sie sich unverbindlich für weitere Informationen registrieren:\nhttps://p.exporo.de/registrierung/#reflink\n\nBei Fragen zur Plattform oder zu aktuellen Immobilienprojekten ist das Team von Exporo telefonisch \nunter 040 210 91 73 -00 oder E-Mail über info@exporo.de zu erreichen.\n\nViele Grüße\n#partnername\n\nP.S.: Die Presse hat bereits mehrfach über Exporo berichtet:\nhttps://exporo.de/presse/#reflink',NULL,NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39');
/*!40000 ALTER TABLE `mailings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2018_01_01_000000_create_action_events_table',1),(2,'2018_07_17_090220_initial_migration',1),(3,'2018_07_20_135040_user_soft_delete',1),(4,'2018_07_22_232721_add_initial_roles',1),(5,'2018_08_01_154704_add_runtime_to_projects',1),(6,'2018_08_13_121725_add_manage_bills_permission',1),(7,'2018_08_13_121725_add_manage_projects_permission',1),(8,'2018_08_13_121725_add_manage_schemas_permission',1),(9,'2018_08_15_083453_add_details_to_companies',1),(10,'2018_08_15_124530_add_iban_to_user_details',1),(11,'2018_08_19_215841_add_primary_index_to_investments',1),(12,'2018_08_22_083935_create_provision_types',1),(13,'2018_08_22_084442_create_provisions',1),(14,'2018_08_22_125626_add_provision_type_to_project',1),(15,'2018_08_28_115806_add_manage_provision_type',1),(16,'2018_09_07_152845_update_audits_table',1),(17,'2018_09_07_154951_add_email_verification_to_users',1),(18,'2018_09_19_133141_change_iban_to_text',1),(19,'2018_09_19_160251_rename_provision_tables',1),(20,'2018_09_24_144245_add_indices_to_commission_tables',1),(21,'2018_09_27_222753_add_bonus_amount_to_commissions',1),(22,'2018_10_23_082717_migrate_commission_bonuses',1),(23,'2018_10_23_082737_drop_bonus_fields_from_user_details',1),(24,'2018_10_23_142716_create_bonus_bundles_table',1),(25,'2018_10_23_144112_add_is_project_type_to_commission_types',1),(26,'2018_10_24_093143_migrate_morph_tos_after_model_namespace_change',1),(27,'2018_10_25_145903_add_pdf_created_to_bill',1),(28,'2018_10_26_202239_add_is_overhead_to_commission_types',1),(29,'2018_10_27_153446_copy_overhead_commission_bonuses',1),(30,'2018_10_29_143138_add_child_user_id_to_commissions_table',1),(31,'2018_10_29_161437_create_failed_jobs_table',1),(32,'2018_11_02_093056_add_term_cancelled_to_user',1),(33,'2018_11_02_100635_add_manage_bonus_bundles_permission',1),(34,'2018_11_02_130906_change_password_to_optional',1),(35,'2018_11_09_081401_add_bic_to_partner',1),(36,'2018_11_12_105739_change_vat_included_nullable',1),(37,'2018_11_14_094730_change_length_of_bic',1),(38,'2018_11_23_133644_change_length_of_company_user_details',1),(39,'2018_11_26_132156_add_field_for_username_company',1),(40,'2018_11_26_164338_touch_users_creating_display_name',1),(41,'2018_11_30_101118_add_user_id_index_to_commissions_table',1),(42,'2018_12_05_131429_add_primary_to_projects',1),(43,'2018_12_07_155853_add_whitelisting_to_commission_bundles',1),(44,'2018_12_28_153108_create_mailings_table',1),(45,'2018_12_28_164132_add_manage_mailings_permission',1),(46,'2019_01_10_092936_add_effective_from_agb',1),(47,'2019_01_14_152055_create_links_table',1),(48,'2019_01_14_164132_add_manage_links_permission',1),(49,'2019_01_16_095450_create_banners_table',1),(50,'2019_01_16_095459_create_banner_sets_table',1),(51,'2019_01_16_114020_add_manage_banners_permission',1),(52,'2019_01_16_114031_add_manage_banner_sets_permission',1),(53,'2019_01_28_140010_add_download_bills_permissions',1),(54,'2019_01_28_141800_create_can_be_billed_permission',1),(55,'2019_02_04_113318_convert_vat_included_to_vat_status',1),(56,'2019_02_04_161730_add_bonus_id_to_investments',1),(57,'2019_02_15_110630_add_billing_permission_to_all_relevant_users',1),(58,'2019_02_22_084821_add_pdf_created_index_to_bills_table',1),(59,'2019_02_25_143624_add_manage_commission_bonuses_permission',1),(60,'2019_03_04_111713_add_mail_sent_at_date_to_bills_table',1),(61,'2019_03_04_130304_fix_mail_sent_at_column_in_bills_table',1),(62,'2019_03_08_150055_split_up_permissions_into_resources',1),(63,'2019_03_12_073133_adds_financing_entity_id',1),(64,'2019_03_12_073155_adds_immo_tool_project_id',1),(65,'2019_03_12_081629_adds_legal_setup_to_projects',1),(66,'2019_03_18_091918_rename_affiliate_permissions',1),(67,'2019_03_18_092746_add_investor_permissions',1),(68,'2019_03_18_145518_add_contracts_table',1),(69,'2019_03_18_162137_add_contract_permissions',1),(70,'2019_03_18_164205_add_defaults_to_contract_templates_table',1),(71,'2019_03_22_111735_add_protected_status_to_permissions_table',1),(72,'2019_03_29_104317_remove_user_id_from_commission_bonuses_table',1),(73,'2019_05_10_000000_add_fields_to_action_events_table',1),(74,'2019_07_29_144816_migrate_bonus_bundles_to_contract_templates',1),(75,'2019_08_14_104448_add_html_field_to_mailings_table',1),(76,'2019_08_19_124419_create_link_instances_table',1),(77,'2019_08_19_124425_create_link_clicks_table',1),(78,'2019_08_23_111406_add_email_field_to_investors_table',1),(79,'2019_08_23_140457_fix_contract_permissions',1),(80,'2019_08_23_154819_move_commission_bonus_permissions_under_contract_group',1),(81,'2019_08_30_095020_add_soft_deletes_to_investors_table',1),(82,'2019_08_30_115533_add_no_api_limit_permission',1),(83,'2019_08_30_115750_add_synced_at_to_investors_table',1),(84,'2019_08_30_135350_add_default_contract_template_to_companies',1),(85,'2019_09_02_120645_rename_contract_edit_permissions',1),(86,'2019_09_02_125614_create_link_shortener_permissions',1),(87,'2019_09_06_133754_add_index_to_link_clicks',1),(88,'2019_09_06_151305_add_link_user_relationship',1),(89,'2019_10_04_083810_add_banner_link_permissions',1),(90,'2019_10_04_094832_create_banner_links_table',1),(91,'2019_10_04_100140_convert_banner_set_urls_to_banner_links',1),(92,'2019_10_04_164820_remove_urls_field_from_banner_sets',1),(93,'2019_10_04_170648_make_link_instances_morphable',1),(94,'2019_10_07_094209_mailings_variables',1),(95,'2019_10_07_121009_add_comission_permissions',1),(96,'2019_10_07_155642_add_user_login_permission',1),(97,'2019_10_11_105058_split_up_contracts',1),(98,'2019_10_25_063943_add_manage_embed_permission',1),(99,'2019_11_04_091058_add_project_fields',1),(100,'2019_11_04_132125_add_is_exclusive_and_allow_overhead_fields_to_contracts_table',1),(101,'2019_11_04_170325_fix_termination_dates_on_contracts',1),(102,'2019_11_11_093549_set_allow_overhead_flag_on_partner_contract_based_on_product_contract_bonuses',1),(103,'2019_11_11_130234_add_accept_contracts_permission',1),(104,'2019_11_11_130428_add_pdf_generated_at_to_contracts',1),(105,'2019_11_25_162551_add_public_flag_to_commission_types',1),(106,'2020_01_14_103949_add_document_contract_permission',1),(107,'2020_01_14_111445_add_dashboard_permission',1),(108,'2020_01_14_113732_add_protected_to_embed_permissions',1),(109,'2020_01_29_093913_add_link_to_investor',1),(110,'2020_01_29_122724_add_created_at_index_to_investments_table',1),(111,'2020_02_04_125504_add_usage_to_link_instances',1),(112,'2020_02_04_160815_add_indexes_to_investors_table',1),(113,'2020_02_04_172035_add_type_index_to_projects_table',1),(114,'2020_02_04_194535_add_gross_index_to_commissions_table',1),(115,'2020_02_07_093702_add_affiliate_dashboard_permission',1),(116,'2020_02_07_110711_add_investment_to_link_clicks_table',1),(117,'2020_02_14_075811_update_api_token_in_users_table',1),(118,'2020_02_14_093719_add_unique_flag_to_users_table',1),(119,'2020_02_18_110935_add_manage_campaign_permission',1),(120,'2020_02_19_090420_create_campaigns_table',1),(121,'2020_02_28_125049_add_campaign_user_relationship',1),(122,'2020_03_13_105603_add_iframe_to_projects_table',1),(123,'2020_03_17_173637_add_fixed_amount_to_commissions_table',1),(124,'2020_03_25_074042_add_link_to_campaigns_table',1),(125,'2020_09_07_074128_add_pending_to_commissions_table',1),(126,'2021_01_07_125103_add_pdp_link_to_projects_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `protected` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (5,'features.contracts.process','web',NULL,'2021-11-11 14:35:38','2021-11-11 14:35:40'),(6,'features.audits.view','web','[\"partner\"]','2021-11-11 14:35:38','2021-11-11 14:35:40'),(7,'features.users.dashboard','web',NULL,'2021-11-11 14:35:38','2021-11-11 14:35:40'),(17,'features.bills.download','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(18,'features.bills.receive','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(20,'management.agbs.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(21,'management.agbs.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(22,'management.agbs.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(23,'management.agbs.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(24,'management.documents.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(25,'management.documents.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(26,'management.documents.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(27,'management.documents.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(28,'management.users.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(29,'management.users.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(30,'management.users.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(31,'management.users.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(32,'management.authorization.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(33,'management.authorization.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(34,'management.authorization.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(35,'management.authorization.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(36,'management.bills.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(37,'management.bills.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(38,'management.bills.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(39,'management.bills.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(40,'management.projects.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(41,'management.projects.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(42,'management.projects.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(43,'management.projects.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(44,'management.schemas.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(45,'management.schemas.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(46,'management.schemas.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(47,'management.schemas.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(48,'management.commission-types.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(49,'management.commission-types.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(50,'management.commission-types.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(51,'management.commission-types.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(56,'management.affiliate.mailings.view','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(57,'management.affiliate.mailings.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(58,'management.affiliate.mailings.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(59,'management.affiliate.mailings.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(60,'management.affiliate.links.view','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(61,'management.affiliate.links.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(62,'management.affiliate.links.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(63,'management.affiliate.links.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(64,'management.affiliate.banners.view','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(65,'management.affiliate.banners.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(66,'management.affiliate.banners.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(67,'management.affiliate.banners.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(68,'management.affiliate.banner-sets.view','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(69,'management.affiliate.banner-sets.create','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(70,'management.affiliate.banner-sets.update','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(71,'management.affiliate.banner-sets.delete','web','[\"partner\"]','2021-11-11 14:35:39','2021-11-11 14:35:40'),(72,'management.contracts.commission-bonuses.view','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(73,'management.contracts.commission-bonuses.create','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(74,'management.contracts.commission-bonuses.update','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(75,'management.contracts.commission-bonuses.delete','web',NULL,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(76,'features.bills.export','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(77,'viewNova','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(78,'management.investments.create','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(79,'management.investments.delete','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(80,'management.investments.update','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(81,'management.investments.view','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(82,'management.investors.create','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(83,'management.investors.delete','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(84,'management.investors.update','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(85,'management.investors.view','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(86,'management.contracts.create','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(87,'management.contracts.delete','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(88,'management.contracts.update','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(89,'management.contracts.view','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(90,'management.contracts.templates.create','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(91,'management.contracts.templates.delete','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(92,'management.contracts.templates.update','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(93,'management.contracts.templates.view','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(94,'management.contracts.update-special-agreement','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(96,'management.contracts.update-cancellation-period','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(97,'management.contracts.update-claim','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(98,'management.contracts.update-vat-details','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(99,'features.api.no-limit','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(100,'features.link-shortener.dashboard','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(101,'features.link-shortener.links','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(102,'features.link-shortener.banners','web',NULL,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(103,'management.commissions.create','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(104,'management.commissions.delete','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(105,'management.commissions.update','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(106,'management.commissions.view','web','[\"partner\"]','2021-11-11 14:35:40','2021-11-11 14:35:40'),(107,'features.users.login','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(108,'management.affiliate.embeds.create','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41'),(109,'management.affiliate.embeds.delete','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41'),(110,'management.affiliate.embeds.update','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41'),(111,'management.affiliate.embeds.view','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(112,'management.contracts.update-is-exclusive','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(113,'management.contracts.update-allow-overhead','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(114,'features.contracts.accept','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(115,'management.documents.view-contracts','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(116,'management.dashboard.view','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(117,'management.affiliate.dashboard.view','web',NULL,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(118,'management.campaigns.create','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41'),(119,'management.campaigns.delete','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41'),(120,'management.campaigns.update','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41'),(121,'management.campaigns.view','web','[\"partner\"]','2021-11-11 14:35:41','2021-11-11 14:35:41');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL COMMENT 'External Project ID',
  `immo_project_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `financing_entity_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_setup` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interest_rate` decimal(8,2) DEFAULT NULL,
  `margin` decimal(8,2) DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `schema_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `capital_cost` decimal(8,2) DEFAULT NULL,
  `launched_at` date DEFAULT NULL,
  `payback_min_at` date DEFAULT NULL,
  `payback_max_at` date DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approved_by` int(10) unsigned DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `commission_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_rate` decimal(8,2) DEFAULT NULL,
  `funding_target` decimal(8,0) DEFAULT NULL,
  `intermediator` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_iframe` tinyint(1) NOT NULL DEFAULT '1',
  `pdp_link` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `projects_schema_id_index` (`schema_id`),
  KEY `projects_approved_by_index` (`approved_by`),
  KEY `projects_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (6,3,'2021-11-11 14:35:38','2021-11-11 14:35:38'),(7,1,'2021-11-11 14:35:38','2021-11-11 14:35:38'),(7,4,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(17,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(17,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(18,1,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(36,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(37,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(38,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(39,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(40,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(41,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(42,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(43,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(44,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(45,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(46,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(47,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(48,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(49,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(50,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(51,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(56,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(56,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(57,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(57,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(58,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(58,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(59,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(59,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(60,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(60,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(61,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(61,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(62,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(62,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(63,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(63,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(64,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(64,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(65,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(65,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(66,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(66,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(67,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(67,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(68,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(68,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(69,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(69,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(70,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(70,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(71,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(71,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(72,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(72,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(73,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(73,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(74,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(74,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(75,2,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(75,3,'2021-11-11 14:35:39','2021-11-11 14:35:39'),(76,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(77,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(78,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(78,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(79,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(79,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(80,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(80,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(81,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(81,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(82,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(82,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(83,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(83,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(84,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(84,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(85,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(85,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(86,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(86,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(87,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(87,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(88,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(88,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(89,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(89,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(90,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(90,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(91,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(91,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(92,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(92,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(93,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(93,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(94,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(94,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(103,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(103,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(104,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(104,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(105,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(105,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(106,2,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(106,3,'2021-11-11 14:35:40','2021-11-11 14:35:40'),(108,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(108,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(109,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(109,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(110,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(110,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(111,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(111,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(112,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(113,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(115,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(115,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(116,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(116,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(117,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(117,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(118,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(118,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(119,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(119,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(120,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(120,3,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(121,2,'2021-11-11 14:35:41','2021-11-11 14:35:41'),(121,3,'2021-11-11 14:35:41','2021-11-11 14:35:41');
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'partner','web','2021-11-11 14:35:38','2021-11-11 14:35:38'),(2,'internal','web','2021-11-11 14:35:38','2021-11-11 14:35:38'),(3,'admin','web','2021-11-11 14:35:38','2021-11-11 14:35:38'),(4,'gesperrt','web','2021-11-11 14:35:39','2021-11-11 14:35:39');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schemas`
--

DROP TABLE IF EXISTS `schemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schemas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `formula` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schemas`
--

LOCK TABLES `schemas` WRITE;
/*!40000 ALTER TABLE `schemas` DISABLE KEYS */;
/*!40000 ALTER TABLE `schemas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details` (
  `id` int(10) unsigned NOT NULL,
  `company` text COLLATE utf8mb4_unicode_ci,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` enum('Dr.','Dr. med.','Prof. Dr.','Prof.') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salutation` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` text COLLATE utf8mb4_unicode_ci,
  `address_street` text COLLATE utf8mb4_unicode_ci,
  `address_number` text COLLATE utf8mb4_unicode_ci,
  `address_addition` text COLLATE utf8mb4_unicode_ci,
  `address_zipcode` text COLLATE utf8mb4_unicode_ci,
  `address_city` text COLLATE utf8mb4_unicode_ci,
  `phone` text COLLATE utf8mb4_unicode_ci,
  `website` text COLLATE utf8mb4_unicode_ci,
  `vat_id` text COLLATE utf8mb4_unicode_ci,
  `tax_office` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vat_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `vat_included` tinyint(1) NOT NULL DEFAULT '0',
  `iban` text COLLATE utf8mb4_unicode_ci,
  `bic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_details`
--

LOCK TABLES `user_details` WRITE;
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned DEFAULT NULL,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `api_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `term_cancelled_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_api_token_unique` (`api_token`),
  UNIQUE KEY `users_email_company_id_unique` (`email`,`company_id`),
  KEY `users_company_id_index` (`company_id`),
  KEY `users_email_index` (`email`),
  KEY `users_parent_id_index` (`parent_id`),
  KEY `users_api_token_index` (`api_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-11 14:35:41
