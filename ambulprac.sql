-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 10, 2012 at 04:54 PM
-- Server version: 5.5.21
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ambulprac`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subsection_id` int(11) NOT NULL,
  `content_name` varchar(150) NOT NULL,
  `content_slug` varchar(200) NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subsection_id` (`subsection_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `subsection_id`, `content_name`, `content_slug`, `order`) VALUES
(1, 6, 'Scope of Practice Grid Advanced Practice Providers 2011', 'APP-Grid-2011-final.doc', 1),
(2, 6, 'Delegation of Services Agreement 2011', 'DSA-final.pdf', 2),
(3, 6, 'NP/CNM Standardized Procedures and Process Protocols for Ambulatory and Hospital Based Practice 2011', 'NP-CNM-Standardized-Procedures-and-PP-2011-final.pdf', 3),
(4, 6, 'PA Practice Guidelines and Protocols for Ambulatory and Hospital Based Practice 2011', 'PA-Practice-Guidelines-and-Protocols-2011-final.pdf', 4),
(5, 6, 'Competency Foundation Form', 'Competency-documentation.pdf', 5),
(6, 6, 'Chart Review Form', 'Chart-Review-Form.pdf', 6),
(7, 6, 'Proctoring Form', 'Proctoring-Form.pdf', 7),
(8, 7, 'Admitting/Transfer/Discharge Orders for PAs', 'Admit-Trans-Disch-Orders-PA.pdf', 1),
(9, 7, 'Administrative Certified Nurse Midwife Position Definition', 'AdminCMN.pdf', 2),
(10, 7, 'Certified Nurse Midwife Position Definition', 'CNM.pdf', 3),
(11, 7, 'Nurse Practitioner - Position Definition', 'NP-Position-Definition.pdf', 4),
(12, 7, 'Physician Assistant - Position Definition', 'PA-Position-Definition.pdf', 5),
(13, 13, 'Legal Issues', 'Legal-Issues.pdf', 1),
(14, 13, 'Legal Issues: MAs Dilating Drops', 'OpthSOPbills10-31-06.pdf', 2),
(15, 14, 'Bladder Instillation Policy and Procedure', 'BCGJan09FINAL.pdf', 1),
(16, 14, 'BCG Competency Validation BCG Administration', 'Competency-Validation-BCG-Administration.pdf', 2),
(17, 14, 'BCG Product Preparation', 'Competency-Validation-BCG-Prodect-Prep.pdf', 3),
(18, 14, 'BCG Treatment', 'BCG-Treatment.ppt', 4),
(19, 14, 'Medication Administration by Medical Assistants', 'MA-Medication-Administration-PnP.pdf', 5),
(20, 14, 'In-Basket Item Management by Office Staff', 'In-Basket', 6),
(21, 14, 'Blood Pressure Monitoring - Blood Pressure Check Visit', 'BP-Monitoring.pdf', 7),
(22, 14, 'Regional SP Admin of Emergency Meds by RNs', 'Regional-SP-Admin-of-Emergency-Meds-by-RNs-in-MOB.pdf', 8),
(23, 14, 'Regional P&P for Proactive Care Support', 'Regional-Proactive-Care-Support-PnP.pdf', 9),
(24, 14, 'Telephone Advice for Over-the-Counter Meds and Home Treatment', 'Telephone-Advice-OtC-Meds.pfg', 10),
(25, 14, 'Regional Telephone Management of UTI P&P', 'Regional-Telephone-Management-UTI-PnP.pdf', 11);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) NOT NULL,
  `section_slug` varchar(50) NOT NULL,
  `section_dropdown` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section_slug` (`section_slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `section_name`, `section_slug`, `section_dropdown`) VALUES
(1, 'ACPC', 'ACPC', 1),
(2, 'Clinical Practice', 'Clinical-Practice', 1),
(3, 'Education & Research', 'Education-Research', 1),
(4, 'Quality & Safety', 'Quality-Safety', 1),
(5, 'Connections & Links', 'Connections-Links', 0),
(6, 'Tools & Reasources', 'Tools-Reasources', 1),
(7, 'Community Partners', 'Community-Partners', 1),
(8, 'Medical Centers', 'Medical-Centers', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subsection`
--

CREATE TABLE IF NOT EXISTS `subsection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subsection_name` varchar(150) NOT NULL,
  `subsection_caption` text NOT NULL,
  `section_id` int(1) NOT NULL,
  `subsection_slug` varchar(50) NOT NULL,
  `order` smallint(6) NOT NULL,
  `outside_link` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `subsection`
--

INSERT INTO `subsection` (`id`, `subsection_name`, `subsection_caption`, `section_id`, `subsection_slug`, `order`, `outside_link`, `locked`) VALUES
(1, 'ACPC Members', 'Meet the Kaiser Permanente ACPC members', 1, 'ACPC-members', 2, 0, 0),
(2, 'Committee Charter', 'View the Ambulatory Practice Committee Charter', 1, 'committee-charter', 3, 0, 0),
(3, 'How to Contact Us', 'Click here to contact an Ambulatory Practice member', 1, 'ACPC-contact', 4, 0, 0),
(4, 'A Message from Administration', 'View the message from administration, Terry Bream', 1, 'message-from-admin', 1, 0, 0),
(5, 'Strategic Initiatives', 'SCAL Kaiser Permanente Ambulatory Clinical Services Strategic Priorities "Big Dots"', 1, 'big-dots', 5, 0, 0),
(6, 'Advanced Practice Documents', 'View the Advanced Practice Documents', 2, 'advanced-practice', 1, 0, 0),
(7, 'Advanced Practice Provider Requirements', 'View the Advanced Practice Providers Requirements', 2, 'advanced-practice-requirements', 2, 0, 0),
(8, 'Allied Health Professional (AHP) Peer Review', 'Link to the AHP Peer Review', 2, 'temp-slug', 3, 0, 0),
(9, 'Ambulatory Primary Care RN Practice Compendium', 'View the Ambulatory Primary Care RN Practice Compendium documents', 2, 'temp-slug', 4, 0, 0),
(10, 'BP Spot Check Program 2012', 'Blood Pressure Spot Check Program', 2, 'bp-spot-check', 5, 0, 0),
(11, 'Clinical Practice Guidelines', 'View the KP Clincial Practice Guidelines', 2, 'temp-slug', 6, 0, 0),
(12, 'KP.org Lab Result Information', 'KP.org - Related Lab Result Information', 2, 'temp-slug', 7, 0, 0),
(13, 'Legal Issues', 'View Ambulatory Nursing Legal Issues', 2, 'legal-issues', 8, 0, 0),
(14, 'Policies & Procedures', 'View the KP Ambulatory Policies and Procedures', 2, 'policies-procedures', 9, 0, 0),
(15, 'Scope of Practice', 'View the KP Scope of Practice information', 2, 'scope-of-practice', 10, 0, 0),
(16, 'Standing Orders', '', 2, 'standing-orders', 11, 0, 0),
(17, 'Sterilization', '', 2, 'sterilization', 12, 0, 0),
(18, 'Surgical Non-MD Grid', 'View the Surgical Non-MD Grid and Policies and Procedures', 2, 'surgical-non-md-gird', 13, 0, 0),
(19, 'Vaccines for Children Program', 'Vaccines for Children, Storage and Handling', 2, 'temp-slug', 14, 0, 0),
(20, 'Affiliated Students', 'View information on preceptorships with affiliated schools, student preceptor liasons, and downloadable forms', 3, 'affiliated-students', 1, 0, 0),
(21, 'CME CEU Funding/Reimbursment', 'Information on CME CEU Funding/Reimbursement', 3, 'CME-funding', 2, 0, 0),
(22, 'Education', 'View the many online educational opportunities', 3, 'education', 3, 0, 0),
(23, 'KP Learn', 'View educational opportunities on KP Learn', 3, 'temp-slug', 4, 0, 0),
(24, 'National Patient Care Services - Clinical E-Learning', 'National Patient Care Services Clinical E-Learning Web Site featuring over 40 clinical e-learning courses including High Alert Medication safety', 3, 'temp-slug', 5, 0, 0),
(26, 'Orientation', 'Orientation programs for MAs, LVNs, and RNs', 3, 'orientation', 7, 0, 0),
(27, 'Research - Integrative Reviews', 'View topics on research and integrative reviews. Now posted, injection aspiration. Read a research paper by Brittney Miller', 3, 'integrative-reviews', 8, 0, 0),
(28, 'Research Resource Warehouse', 'Link to Nursing Pathways research page', 3, 'temp-slug', 9, 0, 0),
(29, 'Research Tools', 'View the Nursing Pathways research tools', 3, 'temp-slug', 10, 0, 0),
(30, 'Respiratory Therapy Education', 'View the Respiratory Therapy Education oppurtunities ', 3, 'respiratory-therapy-education', 11, 0, 0),
(31, 'Respiratory Therapy Education', 'View the Respiratory Therapy Education oppurtunities ', 3, 'public-respiratory-therapy-education', 12, 0, 0),
(32, 'SCPMG Online Learning Portal', 'View e-learning educational opportunities at the SCPMG Online Learning Portal', 3, 'temp-slug', 13, 0, 0),
(33, 'Symposia', 'View the upcoming educational symposia', 3, 'temp-slug', 14, 0, 0),
(34, 'UNAC/UHCP Joint Labor & Management', 'Guidelines for the request, approval, notification, and payment processes for education funded and approved by the UNAC/UHCP Joint Labor Management Education Committee', 3, 'temp-slug', 15, 0, 0),
(35, 'Department of Care and Service Quality', 'View the Department of Care & Services Quality website', 4, 'temp-slug', 1, 0, 0),
(36, 'Hand Hygiene - LMP Oversight', 'Based on the Regional LMP Oversight Committee''s recommendation that each medical center Hand Hygiene team collaborate with their local Medical center LMP council, attached is a powerpoint presentation that can be used for local presentations.', 4, 'temp-slug', 2, 0, 0),
(37, 'Hand Hygiene - Nursing Education', 'Hand Hygiene Education for Nursing', 4, 'temp-slug', 3, 0, 0),
(38, 'AAACN Resources', 'View the AAACN Resources (books, documents, etc)', 6, 'temp-slug', 1, 0, 0),
(39, 'Articulate Rapid E-Learning Guide', 'Articulate Rapid E-Learning Guide', 6, 'temp-slug', 2, 0, 0),
(40, 'Audit Forms', 'Download/view audit forms - facility site review checklist for the California Department of Public Health , etc', 6, 'audit-forms', 3, 0, 0),
(41, 'Caring Book', 'Caring Book', 6, 'caring-book', 4, 0, 0),
(42, 'Clinical Laboratory', 'Clinical Laboratory Information', 6, 'clinical-laboratory', 5, 0, 0),
(43, 'Competency Validation Tools (CVTs)', '', 6, 'CVT', 6, 0, 0),
(44, 'DA Training Seminar', 'View the workflow efficiency documents', 6, 'temp-slug', 7, 0, 0),
(45, 'Health Connect Grid RN, LVN, MA', '', 6, 'temp-slug', 8, 0, 0),
(46, 'KP Clinical Library', 'Link to Kaiser Permanente Clinical Library', 6, 'temp-slug', 9, 0, 0),
(47, 'Nurse Educator Resource Guide - CB', 'Nurse Educator Resource Guide for non-KP', 6, 'temp-slug', 10, 0, 0),
(48, 'Nurse Educator Resource Guide - KP', 'Nurse Educator Resource Guide for KP Employees', 6, 'temp-slug', 11, 0, 0),
(49, 'Proactive Office Support (POS)', 'PowerPoint education modules and supporting material for Proactive Office Support', 6, 'temp-slug', 12, 0, 0),
(50, 'Proactive Office Support - Turnaround time for call center messages', 'Proactive Office Support - Turnaround time for call center messages', 6, 'temp-slug', 13, 0, 0),
(51, 'Professional Portfolio', 'Professional portfolio resources', 6, 'professional-portfolio', 14, 0, 0),
(52, 'Registry/Traveler Forms and Information', 'Forms and information for registry and travelers', 6, 'temp-slug', 15, 0, 0),
(53, 'SkillStat Learning', 'Check out the tools and library on this site for help with learning about the heart, EKG rhythms, cardiac trivia games and more', 6, 'temp-slug', 16, 0, 0),
(54, 'Terms and Definitions', '', 6, 'terms-definitions', 17, 0, 0),
(55, 'Community Benefit Annual Report', 'View the community benefit annual reports', 7, 'temp-slug', 1, 0, 0),
(56, 'Community Outreach E-Learning', 'View the Community Outreach E-Learning site', 7, 'temp-slug', 2, 0, 0),
(57, 'KP Community Benefit', 'View information on KP Community Benefit', 7, 'temp-slug', 3, 0, 0),
(58, 'Nurse Educator Resource Guide - CB', 'Nurse Educator Resource Guide for non-KP', 7, 'temp-slug', 4, 0, 0),
(59, 'Nursing Research Education Modules', 'Nursing Research Education Modules', 7, 'temp-slug', 5, 0, 0),
(60, 'Antelope Valley', '', 8, 'antelope-valley', 1, 0, 0),
(61, 'Baldwin Park', '', 8, 'baldwin-park', 2, 0, 0),
(62, 'Downey', '', 8, 'downey', 3, 0, 0),
(63, 'Fontana', '', 8, 'fontana', 4, 0, 0),
(64, 'Kern County', '', 8, 'kern-county', 5, 0, 0),
(65, 'Los Angeles', '', 8, 'los-angeles', 6, 0, 0),
(66, 'OC - Irvine', '', 8, 'irvine', 7, 0, 0),
(67, 'Orange County', '', 8, 'orange-county', 8, 0, 0),
(68, 'Panorama City', '', 8, 'panorama-city', 9, 0, 0),
(69, 'Riverside', '', 8, 'riverside', 10, 0, 0),
(70, 'San Diego', '', 8, 'san-diego', 11, 0, 0),
(71, 'South Bay', '', 8, 'south-bay', 12, 0, 0),
(72, 'West Los Angeles', '', 8, 'west-los-angeles', 13, 0, 0),
(73, 'Woodland Hills', '', 8, 'woodland hills', 14, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(40) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kp_employee` tinyint(1) NOT NULL DEFAULT '0',
  `reloginDigest` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `val_key` varchar(40) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
