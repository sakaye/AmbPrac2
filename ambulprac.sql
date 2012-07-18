-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 18, 2012 at 03:10 PM
-- Server version: 5.5.21
-- PHP Version: 5.3.10

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
  `name` varchar(150) NOT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `order` smallint(6) NOT NULL,
  `type` smallint(1) NOT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subsection_id` (`subsection_id`),
  KEY `content_slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `subsection_id`, `name`, `slug`, `order`, `type`, `URL`, `file_name`) VALUES
(1, 6, 'Scope of Practice Grid Advanced Practice Providers 2011', NULL, 1, 2, NULL, 'test.pdf'),
(2, 6, 'Delegation of Services Agreement 2011', NULL, 2, 2, NULL, 'DSA-final.pdf'),
(3, 6, 'NP/CNM Standardized Procedures and Process Protocols for Ambulatory and Hospital Based Practice 2011', NULL, 3, 2, NULL, 'NP-CNM-Standardized-Procedures-and-PP-2011-final.pdf'),
(4, 6, 'PA Practice Guidelines and Protocols for Ambulatory and Hospital Based Practice 2011', NULL, 4, 2, NULL, 'PA-Practice-Guidelines-and-Protocols-2011-final.pdf'),
(5, 6, 'Competency Foundation Form', NULL, 5, 2, NULL, 'Competency-documentation.pdf'),
(6, 6, 'Chart Review Form', NULL, 6, 2, NULL, 'Chart-Review-Form.pdf'),
(7, 6, 'Proctoring Form', NULL, 7, 2, NULL, 'Proctoring-Form.pdf'),
(8, 7, 'Admitting/Transfer/Discharge Orders for PAs', NULL, 1, 2, NULL, 'Admit-Trans-Disch-Orders-PA.pdf'),
(9, 7, 'Administrative Certified Nurse Midwife Position Definition', NULL, 2, 2, NULL, 'AdminCMN.pdf'),
(10, 7, 'Certified Nurse Midwife Position Definition', NULL, 3, 2, NULL, 'CNM.pdf'),
(11, 7, 'Nurse Practitioner - Position Definition', NULL, 4, 2, NULL, 'NP-Position-Definition.pdf'),
(12, 7, 'Physician Assistant - Position Definition', NULL, 5, 2, NULL, 'PA-Position-Definition.pdf'),
(13, 13, 'Legal Issues', NULL, 1, 2, NULL, 'Legal-Issues.pdf'),
(14, 13, 'Legal Issues: MAs Dilating Drops', NULL, 2, 2, NULL, 'OpthSOPbills10-31-06.pdf'),
(15, 14, 'Bladder Instillation Policy and Procedure', NULL, 1, 2, NULL, 'BCGJan09FINAL.pdf'),
(16, 14, 'BCG Competency Validation BCG Administration', NULL, 2, 2, NULL, 'Competency-Validation-BCG-Administration.pdf'),
(17, 14, 'BCG Product Preparation', NULL, 3, 2, NULL, 'Competency-Validation-BCG-Prodect-Prep.pdf'),
(18, 14, 'BCG Treatment', NULL, 4, 2, NULL, 'BCG-Treatment.ppt'),
(19, 14, 'Medication Administration by Medical Assistants', NULL, 5, 2, NULL, 'MA-Medication-Administration-PnP.pdf'),
(20, 14, 'In-Basket Item Management by Office Staff', 'In-Basket', 6, 0, NULL, NULL),
(21, 14, 'Blood Pressure Monitoring - Blood Pressure Check Visit', NULL, 7, 2, NULL, 'BP-Monitoring.pdf'),
(22, 14, 'Regional SP Admin of Emergency Meds by RNs', NULL, 8, 2, NULL, 'Regional-SP-Admin-of-Emergency-Meds-by-RNs-in-MOB.pdf'),
(23, 14, 'Regional P&P for Proactive Care Support', NULL, 9, 2, NULL, 'Regional-Proactive-Care-Support-PnP.pdf'),
(24, 14, 'Telephone Advice for Over-the-Counter Meds and Home Treatment', NULL, 10, 2, NULL, 'Telephone-Advice-OtC-Meds.pdf'),
(25, 14, 'Regional Telephone Management of UTI P&P', NULL, 11, 2, NULL, 'Regional-Telephone-Management-UTI-PnP.pdf'),
(26, 22, 'GI Core M1', 'GI-CoreM1', 1, 3, NULL, NULL),
(27, 75, 'Allied Health Professional (AHP) Peer Review', NULL, 1, 1, 'http://kpnet.kp.org:81/california/qmrs/main/peer/index.html', NULL),
(28, 75, 'Ambulatory Primary Care - RN Practice Compendium', NULL, 2, 1, 'http://kpnet.kp.org/nursing/national/practice/rnambulatory/', NULL),
(29, 75, 'Clinical Practice Guidelines', NULL, 3, 1, 'http://cl.kp.org/pkc/scal/cpg/cpg/index.html', NULL),
(30, 76, 'KP Learn.org', NULL, 1, 1, 'http://learn.kp.org/', NULL),
(31, 76, 'National Patient Care Services - Clinical E-Learning', NULL, 2, 1, 'http://nursingpathways.kp.org/national/learning/cel/index.html', NULL),
(32, 76, 'NursingPathways.org - Research Resource Warehouse', NULL, 3, 1, 'http://nursingpathways.kp.org/scal/research/resources/index.html', NULL),
(33, 76, 'NursingPathways.org - Research Tools', NULL, 4, 1, 'http://nursingpathways.kp.org/scal/research/projects/tools/index.html', NULL),
(34, 76, 'SCPMG Online Learning Portal.org', NULL, 5, 1, 'http://scpmglearning.org/', NULL),
(35, 76, 'KPSymposia.com', NULL, 6, 1, 'http://kpsymposia.com/', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `dropdown` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section_slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `name`, `slug`, `dropdown`) VALUES
(1, 'ACPC', 'ambulatory-clinical-practice-committee', 1),
(2, 'Clinical Practice', 'clinical-practice', 1),
(3, 'Education & Research', 'education-research', 1),
(4, 'Quality & Safety', 'quality-safety', 1),
(5, 'Connections & Links', 'connections-links', 0),
(6, 'Tools & Reasources', 'tools-resources', 1),
(7, 'Community Partners', 'community-partners', 1),
(8, 'Medical Centers', 'medical-centers', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subsection`
--

CREATE TABLE IF NOT EXISTS `subsection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `caption` text NOT NULL,
  `section_id` int(1) NOT NULL,
  `slug` varchar(50) DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `order` smallint(6) NOT NULL,
  `locked` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`),
  KEY `subsection_slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `subsection`
--

INSERT INTO `subsection` (`id`, `name`, `caption`, `section_id`, `slug`, `URL`, `order`, `locked`) VALUES
(1, 'Ambulatory Clinical Practice Committee Members', 'Meet the Kaiser Permanente ACPC members', 1, 'members', NULL, 2, NULL),
(2, 'Committee Charter', 'Ambulatory Practice Committee Charter', 1, 'committee-charter', NULL, 3, NULL),
(4, 'A Message from Administration', 'View the message from administration', 1, 'message-from-admin', NULL, 1, NULL),
(5, 'Strategic Initiatives', 'SCAL Kaiser Permanente Ambulatory Clinical Services Strategic Priorities "Big Dots"', 1, 'big-dots', NULL, 5, NULL),
(6, 'Advanced Practice Provider Documents', 'View the Advanced Practice Documents', 2, 'advanced-practice', NULL, 1, NULL),
(7, 'Advanced Practice Provider Requirements', 'View the Advanced Practice Providers Requirements', 2, 'advanced-practice-requirements', NULL, 2, NULL),
(10, 'BP Spot Check Program 2012', 'Blood Pressure Spot Check Program', 2, 'bp-spot-check', NULL, 3, NULL),
(13, 'Legal Issues', 'Ambulatory Nursing Legal Issues', 2, 'legal-issues', NULL, 5, NULL),
(14, 'Policies & Procedures', 'KP Ambulatory Policies and Procedures', 2, 'policies-procedures', NULL, 6, NULL),
(15, 'Scope of Practice', 'KP Scope of Practice information', 2, 'scope-of-practice', NULL, 7, NULL),
(16, 'Standing Orders', '', 2, 'standing-orders', NULL, 8, NULL),
(17, 'Sterilization', '', 2, 'sterilization', NULL, 9, NULL),
(18, 'Surgical Non-MD Grid', 'Surgical Non-MD Grid and Policies and Procedures', 2, 'surgical-non-md-gird', NULL, 10, NULL),
(20, 'Affiliated Students', 'Information on preceptorships with affiliated schools, student preceptor liasons, and downloadable forms', 3, 'affiliated-students', NULL, 1, NULL),
(21, 'CME CEU Funding/Reimbursment', 'Information on CME CEU Funding/Reimbursement', 3, 'CME-funding', NULL, 2, NULL),
(22, 'Nursing Education', 'Online educational opportunities', 3, 'education', NULL, 5, NULL),
(26, 'Orientation', 'Orientation programs for MAs, LVNs, and RNs', 3, 'orientation', NULL, 7, NULL),
(27, 'Research - Integrative Reviews', 'Topics on research and integrative reviews. Now posted, injection aspiration. Read a research paper by Brittney Miller', 3, 'integrative-reviews', NULL, 8, NULL),
(30, 'Respiratory Therapy Education', 'Respiratory Therapy Education oppurtunities ', 3, 'respiratory-therapy-education', NULL, 11, NULL),
(31, 'Respiratory Therapy Education (Public)', 'Public Respiratory Therapy Education oppurtunities ', 3, 'public-respiratory-therapy-education', NULL, 12, NULL),
(34, 'UNAC/UHCP Joint Labor & Management', 'Guidelines for the request, approval, notification, and payment processes for education funded and approved by the UNAC/UHCP Joint Labor Management Education Committee', 3, 'UNAC/UHCP-joint-labor', NULL, 15, NULL),
(35, 'Department of Care and Service Quality', 'Department of Care & Services Quality website', 4, NULL, 'http://kpnet.kp.org:81/california/qmrs/ps', 1, NULL),
(36, 'Hand Hygiene - LMP Oversight', 'Based on the Regional LMP Oversight Committee''s recommendation that each medical center Hand Hygiene team collaborate with their local Medical center LMP council, attached is a powerpoint presentation that can be used for local presentations.', 4, NULL, 'http://dms.kp.org/docushare/dsweb/Get/Document-1178482/handHygMay08.ppt', 2, NULL),
(37, 'Hand Hygiene - Nursing Education', 'Hand Hygiene Education for Nursing', 4, NULL, 'http://dms.kp.org/docushare/dsweb/Get/Document-1178483/NursingHandHyp.ppt', 3, NULL),
(38, 'AAACN Resources', 'AAACN Resources (books, documents, etc)', 6, NULL, 'http://www.aaacn.org/cgi-bin/WebObjects/AAACNMain.woa/1/wa/viewSection?s_id=1073743919&wosid=qQ2i3xHdMMPz22w2Mc86OJ23wfb', 1, NULL),
(39, 'Articulate Rapid E-Learning Guide', 'Articulate Rapid E-Learning Guide', 6, NULL, 'http://ambulatorypractice.org/tools_and_resources/articulate_info_sheet_dsm.pdf', 2, NULL),
(40, 'Audit Forms', 'Download/view audit forms - facility site review checklist for the California Department of Public Health , etc', 6, 'audit-forms', NULL, 3, NULL),
(41, 'Caring Book', 'Caring Book', 6, 'caring-book', NULL, 4, NULL),
(42, 'Clinical Laboratory', 'Clinical Laboratory Information', 6, 'clinical-laboratory', NULL, 5, NULL),
(43, 'Competency Validation Tools (CVTs)', '', 6, 'CVT', NULL, 6, NULL),
(44, 'DA Training Seminar', 'Workflow efficiency documents', 6, 'doc', NULL, 7, NULL),
(45, 'Health Connect Grid RN, LVN, MA', '', 6, 'doc', NULL, 8, NULL),
(46, 'KP Clinical Library', 'Link to Kaiser Permanente Clinical Library', 6, NULL, 'http://cl.kp.org/', 9, NULL),
(47, 'Nurse Educator Resource Guide - CB', 'Nurse Educator Resource Guide for non-KP', 6, 'doc', NULL, 10, NULL),
(48, 'Nurse Educator Resource Guide - KP', 'Nurse Educator Resource Guide for KP Employees', 6, 'doc', NULL, 11, NULL),
(49, 'Proactive Office Support (POS)', 'PowerPoint education modules and supporting material for Proactive Office Support', 6, NULL, 'http://dms.kp.org/docushare/dsweb/View/Collection-165215', 12, NULL),
(50, 'Proactive Office Support - Turnaround time for call center messages', 'Proactive Office Support - Turnaround time for call center messages', 6, 'doc', NULL, 13, NULL),
(51, 'Professional Portfolio', 'Professional portfolio resources', 6, 'professional-portfolio', NULL, 14, NULL),
(52, 'Registry/Traveler Forms and Information', 'Forms and information for registry and travelers', 6, NULL, 'http://kpnet.kp.org:81/california/qmrs/qs/nursingquality/inqip/Reg_DNS/dnsf.htm', 15, NULL),
(53, 'SkillStat Learning', 'Check out the tools and library on this site for help with learning about the heart, EKG rhythms, cardiac trivia games and more', 6, NULL, 'http://www.skillstat.com/learn.htm', 16, NULL),
(54, 'Terms and Definitions', '', 6, 'terms-definitions', NULL, 17, NULL),
(55, 'Community Benefit Annual Report', 'View the community benefit annual reports', 7, NULL, 'http://info.kp.org/communitybenefit/html/about_us/global/about_us_7.html', 1, NULL),
(56, 'Community Outreach E-Learning', 'View the Community Outreach E-Learning site', 7, NULL, 'http://nursingpathways.kp.org/national/communityoutreach/learning/cel.html', 2, NULL),
(57, 'KP Community Benefit', 'Information on KP Community Benefit', 7, NULL, 'http://info.kp.org/communitybenefit/html/index.html', 3, NULL),
(58, 'Nurse Educator Resource Guide - CB', 'Nurse Educator Resource Guide for non-KP', 7, 'doc', NULL, 4, NULL),
(59, 'Nursing Research Education Modules', 'Nursing Research Education Modules', 7, NULL, 'http://nursingpathways.kp.org/scal/research/resources/researchseries/index.html', 5, NULL),
(60, 'Antelope Valley', '', 8, 'antelope-valley', NULL, 1, NULL),
(61, 'Baldwin Park', '', 8, 'baldwin-park', NULL, 2, NULL),
(62, 'Downey', '', 8, 'downey', NULL, 3, NULL),
(63, 'Fontana', '', 8, 'fontana', NULL, 4, NULL),
(64, 'Kern County', '', 8, 'kern-county', NULL, 5, NULL),
(65, 'Los Angeles', '', 8, 'los-angeles', NULL, 6, NULL),
(66, 'OC - Irvine', '', 8, 'irvine', NULL, 7, NULL),
(67, 'Orange County', '', 8, 'orange-county', NULL, 8, NULL),
(68, 'Panorama City', '', 8, 'panorama-city', NULL, 9, NULL),
(69, 'Riverside', '', 8, 'riverside', NULL, 10, NULL),
(70, 'San Diego', '', 8, 'san-diego', NULL, 11, NULL),
(71, 'South Bay', '', 8, 'south-bay', NULL, 12, NULL),
(72, 'West Los Angeles', '', 8, 'west-los-angeles', NULL, 13, NULL),
(73, 'Woodland Hills', '', 8, 'woodland hills', NULL, 14, NULL),
(74, 'Documents', 'Clinical Practice Documents', 2, 'cp-documents', NULL, 7, NULL),
(75, 'Clinical Practice Links', 'Look here for more outside information on Clinical Practice', 2, 'clinical-practice-links', NULL, 4, NULL),
(76, 'Education & Research Links', 'Look here to find outside information on Ambulatory Education', 3, 'education-research-links', NULL, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `username_2` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `last_name`, `first_name`, `password`, `email`, `kp_employee`, `reloginDigest`, `title`, `area`, `active`, `val_key`, `creation_date`) VALUES
(1, 'D747035', 'Sakaye', 'Bryun', '62500856decbedab49c87a389d5387616110166b', 'bryun.t.sakaye@kp.org', 0, 'a8563ba6664b7aff22bb77004a8f0b1510f3f66d', 'CRN', 'Pasadena', 1, '44ad6980f5dbc5acae29f6a82f3cfab62f1fd1f3', '2012-07-06 19:17:49');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
