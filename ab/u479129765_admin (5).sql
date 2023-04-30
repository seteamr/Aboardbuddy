-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 28, 2023 at 09:25 AM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u479129765_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `type` varchar(250) NOT NULL DEFAULT 'Admin',
  `cat_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active,0=deactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`, `type`, `cat_id`, `status`) VALUES
(1, 'Admin', 'admin@gmail.com', '123456', 'admin', 1, 1),
(9, 'Admin', 'adminbuddy@gmail.com', 'buddy123456', 'admin', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `carpool`
--

CREATE TABLE `carpool` (
  `carpool_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `driving_experience` varchar(255) NOT NULL,
  `smoking_habit` varchar(255) NOT NULL,
  `consumed_alcohol` varchar(255) NOT NULL,
  `no_of_passengers` varchar(255) NOT NULL,
  `car_description` text NOT NULL,
  `leave_notes` text NOT NULL,
  `entry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carpool`
--

INSERT INTO `carpool` (`carpool_id`, `student_id`, `location`, `driving_experience`, `smoking_habit`, `consumed_alcohol`, `no_of_passengers`, `car_description`, `leave_notes`, `entry_date`) VALUES
(2, 16, 'Indore', '2', 'Yes', 'Yes', '2', 'hllo', 'jfnsdj', '2023-04-25'),
(4, 56, 'jbh', '1', 'Yes', 'Small', '4', 'jggji', 'gjg', '2023-04-26'),
(5, 2, 'indore', '2', 'No', '2', '3', 'test', 'test', '2023-04-27');

-- --------------------------------------------------------

--
-- Table structure for table `carpool_join`
--

CREATE TABLE `carpool_join` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `carpool_id` int(11) NOT NULL,
  `entry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carpool_join`
--

INSERT INTO `carpool_join` (`id`, `student_id`, `carpool_id`, `entry_date`) VALUES
(1, 2, 1, '2023-04-24'),
(2, 3, 1, '2023-04-24'),
(3, 4, 1, '2023-04-24'),
(4, 1, 2, '2023-04-25'),
(5, 16, 2, '2023-04-26'),
(6, 16, 4, '2023-04-26'),
(7, 56, 8, '2023-04-27');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `country_id`, `name`) VALUES
(1, 1, 'bathurst'),
(2, 1, 'new castle'),
(3, 1, 'wallongong'),
(4, 1, 'gold coast'),
(5, 1, 'perth'),
(6, 1, 'adelaide'),
(7, 1, 'canberra'),
(8, 1, 'sydney'),
(9, 1, 'brisbane'),
(10, 1, 'melbourne'),
(11, 2, 'albany'),
(12, 2, 'new york'),
(13, 2, 'new  jersey '),
(14, 2, 'los angles'),
(15, 2, 'chicago'),
(16, 2, 'boston'),
(17, 2, 'philadelphia'),
(18, 2, 'lynchburg'),
(19, 2, 'madison'),
(20, 2, 'columbia'),
(21, 2, 'tampa'),
(22, 2, 'dallas'),
(23, 3, 'Brighton'),
(24, 3, 'Newcastle-upon-Tyne'),
(25, 3, 'Aberdeen'),
(26, 3, 'Birmingham'),
(27, 3, 'nottingham'),
(28, 3, 'Coventry'),
(29, 3, 'Glasgow'),
(30, 3, 'Manchester'),
(31, 3, 'London'),
(32, 4, 'albany'),
(33, 4, 'new york'),
(34, 4, 'new  jersey '),
(35, 4, 'los angles'),
(36, 4, 'chicago'),
(37, 4, 'boston'),
(38, 4, 'philadelphia'),
(39, 4, 'lynchburg'),
(40, 4, 'madison'),
(41, 4, 'columbia'),
(42, 4, 'tampa'),
(43, 4, 'dallas');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `phone_no`, `message`, `created_at`, `updated_at`) VALUES
(1, 'brajesh', 'b@b.com', '9098343935', 'hello test', '2023-04-08', '0000-00-00'),
(2, 'Sukhdev', 'sukhdev@gmail.com', '91742368436', 'Hello', '2023-04-09', '0000-00-00'),
(3, 'hljsdh', 'hfs@gmail.com', '83724723', 'kjshfs', '2023-04-09', '0000-00-00'),
(4, 'nfhsdk', 'hjdsk@gmail.com', 'u3289402', 'sdjfks', '2023-04-09', '0000-00-00'),
(5, 'ndmfdd', 'dd@gmail.com', 'efr3', 'fdsf', '2023-04-18', '0000-00-00'),
(6, 'Madhavi Latha', 'madhavilath@gmail.com', '9866806854', 'I am facing an issue with my account please reach me as soon as possible to my mailing address. thankyou', '2023-04-21', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `name`) VALUES
(1, 'Australia'),
(2, 'Canada'),
(3, 'United Kingdom'),
(4, 'United States');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `read_message` int(11) NOT NULL,
  `deleted_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `entry_date` date NOT NULL,
  `created_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_id`, `to_id`, `message`, `read_message`, `deleted_by`, `status`, `entry_date`, `created_date_time`) VALUES
(1, 1, 2, '\"hello there\"', 1, '', 0, '2023-03-21', '2023-03-21 13:48:00'),
(2, 1, 3, '\"hello test another\"', 0, '', 0, '2023-03-21', '2023-03-21 13:48:46'),
(3, 7, 11, '\"Hii\"', 1, '', 0, '2023-03-26', '2023-03-26 00:52:09'),
(4, 7, 11, '\"Hello\"', 1, '', 0, '2023-03-26', '2023-03-26 00:54:37'),
(5, 7, 11, '\"Hii\"', 1, '', 0, '2023-03-26', '2023-03-26 00:55:35'),
(6, 7, 11, '\"hii\"', 1, '', 0, '2023-03-26', '2023-03-26 00:56:35'),
(7, 7, 11, '\"sss\"', 1, '', 0, '2023-03-26', '2023-03-26 01:11:50'),
(8, 7, 11, '\"sss\"', 1, '', 0, '2023-03-26', '2023-03-26 01:12:07'),
(9, 7, 11, '\"s\"', 1, '', 0, '2023-03-26', '2023-03-26 01:13:14'),
(10, 7, 11, '\"s\"', 1, '', 0, '2023-03-26', '2023-03-26 01:13:18'),
(11, 7, 11, '\"Hii\"', 1, '', 0, '2023-03-26', '2023-03-26 01:13:52'),
(12, 7, 11, '\"Hiiii\"', 0, '', 0, '2023-03-26', '2023-03-26 21:15:04'),
(13, 7, 11, '\"Hii\"', 0, '', 0, '2023-03-26', '2023-03-26 21:15:32'),
(14, 7, 11, '\"Hii\"', 0, '', 0, '2023-03-26', '2023-03-26 21:23:27'),
(15, 1, 2, '\"hii\"', 1, '', 0, '2023-03-27', '2023-03-27 19:46:32'),
(16, 1, 2, '\"Hello\"', 1, '', 0, '2023-03-27', '2023-03-27 19:46:42'),
(17, 17, 16, '\"Hii\"', 1, '', 0, '2023-03-27', '2023-03-27 19:55:16'),
(18, 16, 17, '\"Hello\"', 1, '', 0, '2023-03-27', '2023-03-27 19:56:42'),
(19, 1, 2, '\"jiii\"', 1, '', 0, '2023-03-27', '2023-03-27 20:36:24'),
(20, 1, 3, '\"helllo\"', 0, '', 0, '2023-03-27', '2023-03-27 20:44:47'),
(21, 17, 16, '\"how are you\"', 1, '', 0, '2023-03-27', '2023-03-27 20:50:26'),
(22, 16, 17, '\"fine\"', 1, '', 0, '2023-03-27', '2023-03-27 20:50:34'),
(23, 17, 16, '\"good\"', 1, '', 0, '2023-03-27', '2023-03-27 20:50:41'),
(24, 18, 19, '\"hello\"', 1, '', 0, '2023-03-27', '2023-03-27 22:39:43'),
(25, 19, 18, '\"hiiii\"', 1, '', 0, '2023-03-27', '2023-03-27 22:39:54'),
(26, 18, 19, '\"i am demo1\"', 1, '', 0, '2023-03-27', '2023-03-27 22:40:07'),
(27, 19, 18, '\"demo 2\"', 1, '', 0, '2023-03-27', '2023-03-27 22:40:16'),
(28, 22, 24, '\"Hello test4\"', 0, '', 0, '2023-03-28', '2023-03-28 09:15:59'),
(29, 22, 23, '\"Hello\"', 1, '', 0, '2023-03-28', '2023-03-28 09:19:07'),
(30, 22, 23, '\"Hello\"', 1, '', 0, '2023-03-28', '2023-03-28 09:19:08'),
(31, 23, 22, '\"hiiii\"', 1, '', 0, '2023-03-28', '2023-03-28 09:20:25'),
(32, 22, 23, '\"How are you?\"', 1, '', 0, '2023-03-28', '2023-03-28 09:20:43'),
(33, 6, 22, '\"hi\"', 0, '', 0, '2023-04-15', '2023-04-15 01:27:34'),
(34, 6, 22, '\"hello\"', 0, '', 0, '2023-04-15', '2023-04-15 01:27:45'),
(35, 38, 39, '\"Hi\"', 1, '', 0, '2023-04-15', '2023-04-15 13:38:00'),
(36, 44, 45, '\"HY\"', 1, '', 0, '2023-04-18', '2023-04-18 04:46:31'),
(37, 44, 46, '\"Hi i have viwed your add i am intrested in it\"', 1, '', 0, '2023-04-18', '2023-04-18 05:05:52'),
(38, 44, 45, '\"hi\"', 1, '', 0, '2023-04-18', '2023-04-18 05:57:09'),
(39, 44, 45, '\"hi\"', 1, '', 0, '2023-04-18', '2023-04-18 05:57:10'),
(40, 44, 45, '\"hello\"', 1, '', 0, '2023-04-18', '2023-04-18 05:59:37'),
(41, 44, 45, '\"hello\"', 1, '', 0, '2023-04-18', '2023-04-18 05:59:37'),
(42, 45, 44, '\"Hi whats app\"', 1, '', 0, '2023-04-18', '2023-04-18 06:03:49'),
(43, 44, 45, '\"my name is \"', 1, '', 0, '2023-04-18', '2023-04-18 06:07:24'),
(44, 44, 45, '\"hi \"', 1, '', 0, '2023-04-18', '2023-04-18 06:13:50'),
(45, 44, 45, '\"sa\"', 1, '', 0, '2023-04-18', '2023-04-18 06:25:13'),
(46, 44, 45, '\"sa\"', 1, '', 0, '2023-04-18', '2023-04-18 06:25:13'),
(47, 44, 46, '\"hi\"', 0, '', 0, '2023-04-18', '2023-04-18 06:56:11'),
(48, 49, 48, '\"Hi hello\"', 1, '', 0, '2023-04-18', '2023-04-18 09:16:21'),
(49, 48, 49, '\"Hello\"', 1, '', 0, '2023-04-18', '2023-04-18 09:16:35'),
(50, 47, 49, '\"hi i have recived your request\"', 1, '', 0, '2023-04-18', '2023-04-18 09:19:49'),
(51, 49, 47, '\"i am intrested in your device\"', 1, '', 0, '2023-04-18', '2023-04-18 09:20:08'),
(52, 51, 50, '\"Hi \"', 1, '', 0, '2023-04-18', '2023-04-18 10:16:11'),
(53, 50, 51, '\"I am intrested in buyin your device\"', 1, '', 0, '2023-04-18', '2023-04-18 10:16:37'),
(54, 50, 51, '\"can we discuss more\"', 1, '', 0, '2023-04-18', '2023-04-18 10:16:44'),
(55, 50, 44, '\"hi\"', 1, '', 0, '2023-04-18', '2023-04-18 10:19:21'),
(56, 1, 2, '\"ddd\"', 1, '', 0, '2023-04-18', '2023-04-18 11:22:59'),
(57, 1, 2, '\"fgdgd\"', 1, '', 0, '2023-04-18', '2023-04-18 11:29:14'),
(58, 1, 3, '\"hy\"', 0, '', 0, '2023-04-18', '2023-04-18 12:44:41'),
(59, 28, 16, '\"Hii\"', 1, '', 0, '2023-04-18', '2023-04-18 23:44:01'),
(60, 28, 16, '\"Hello\"', 1, '', 0, '2023-04-18', '2023-04-18 23:44:07'),
(61, 28, 16, '\"jj\"', 1, '', 0, '2023-04-18', '2023-04-18 23:49:24'),
(62, 1, 7, '\"hello\"', 0, '', 0, '2023-04-19', '2023-04-19 00:15:04'),
(63, 1, 7, '\"how are you\"', 0, '', 0, '2023-04-19', '2023-04-19 00:15:11'),
(64, 50, 51, '\"fw\"', 0, '', 0, '2023-04-21', '2023-04-21 01:25:12'),
(65, 50, 51, '\"fwfwfw\"', 0, '', 0, '2023-04-21', '2023-04-21 01:25:16'),
(66, 50, 51, '\"fwfwf\"', 0, '', 0, '2023-04-21', '2023-04-21 01:25:22'),
(67, 50, 51, '\"fwfwfwfwf\"', 0, '', 0, '2023-04-21', '2023-04-21 01:25:26'),
(68, 50, 51, '\"wfwfwf\"', 0, '', 0, '2023-04-21', '2023-04-21 01:25:36'),
(69, 50, 51, '\"fwfwf\"', 0, '', 0, '2023-04-21', '2023-04-21 01:25:47'),
(70, 44, 53, '\"Hi hello \"', 1, '', 0, '2023-04-21', '2023-04-21 09:58:42'),
(71, 44, 53, '\"How are you\"', 1, '', 0, '2023-04-21', '2023-04-21 09:58:49'),
(72, 53, 44, '\"Hy hi\"', 1, '', 0, '2023-04-21', '2023-04-21 09:59:14'),
(73, 53, 44, '\"i am good\"', 1, '', 0, '2023-04-21', '2023-04-21 09:59:19'),
(74, 53, 44, '\"how about you\"', 1, '', 0, '2023-04-21', '2023-04-21 09:59:28'),
(75, 44, 50, '\"hi i am intrested in your carpoolin to newyork\"', 0, '', 0, '2023-04-21', '2023-04-21 10:03:29'),
(76, 44, 50, '\"is there a chance to hope in\"', 0, '', 0, '2023-04-21', '2023-04-21 10:03:41'),
(77, 53, 54, '\"hi i am intrested in buying your product\"', 1, '', 0, '2023-04-21', '2023-04-21 10:16:30'),
(78, 54, 53, '\"ok lets discuss more\"', 1, '', 0, '2023-04-21', '2023-04-21 10:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `message_request`
--

CREATE TABLE `message_request` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `approved` int(11) NOT NULL,
  `entry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_request`
--

INSERT INTO `message_request` (`id`, `from_id`, `to_id`, `approved`, `entry_date`) VALUES
(1, 1, 2, 1, '2023-03-21'),
(2, 1, 3, 1, '2023-03-21'),
(3, 11, 4, 0, '2023-03-25'),
(4, 11, 7, 1, '2023-03-26'),
(5, 7, 1, 1, '2023-03-27'),
(6, 17, 16, 2, '2023-03-27'),
(7, 19, 18, 1, '2023-03-27'),
(8, 6, 5, 0, '2023-03-28'),
(9, 6, 7, 0, '2023-03-28'),
(10, 24, 22, 1, '2023-03-28'),
(11, 23, 22, 1, '2023-03-28'),
(12, 22, 6, 1, '2023-03-28'),
(13, 18, 1, 0, '2023-03-31'),
(14, 16, 19, 0, '2023-04-02'),
(15, 16, 17, 0, '2023-04-02'),
(16, 16, 1, 0, '2023-04-02'),
(17, 22, 15, 0, '2023-04-03'),
(18, 31, 23, 0, '2023-04-04'),
(19, 31, 6, 0, '2023-04-04'),
(20, 13, 5, 0, '2023-04-06'),
(21, 13, 14, 0, '2023-04-06'),
(22, 16, 28, 1, '2023-04-10'),
(23, 6, 14, 0, '2023-04-15'),
(24, 37, 6, 0, '2023-04-15'),
(25, 39, 38, 1, '2023-04-15'),
(26, 45, 44, 1, '2023-04-18'),
(27, 44, 46, 1, '2023-04-18'),
(28, 44, 14, 0, '2023-04-18'),
(29, 48, 44, 1, '2023-04-18'),
(30, 48, 49, 1, '2023-04-18'),
(31, 49, 47, 1, '2023-04-18'),
(32, 44, 49, 0, '2023-04-18'),
(33, 50, 51, 1, '2023-04-18'),
(34, 44, 50, 1, '2023-04-18'),
(35, 28, 7, 0, '2023-04-18'),
(36, 53, 50, 0, '2023-04-21'),
(37, 53, 44, 1, '2023-04-21'),
(38, 53, 54, 1, '2023-04-21'),
(39, 57, 2, 0, '2023-04-27'),
(40, 58, 2, 0, '2023-04-27');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `property_type` varchar(250) NOT NULL,
  `select_option` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `entry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `student_id`, `city_id`, `property_type`, `select_option`, `description`, `status`, `entry_date`) VALUES
(17, 25, 1, 'Accommodation', 'Sell', 'testttt', 2, '2023-04-08'),
(18, 25, 1, 'Accommodation', 'Sell', 'testttt', 2, '2023-04-10'),
(19, 28, 14, 'Accommodation', 'Sell', 'Hiiiijojljf', 1, '2023-04-10'),
(20, 46, 32, 'Electronics', 'Sell', 'Iphone 14 pro max\r\nNo scratches\r\nAvailable Two colors\r\n45 days used\r\nprice - 700$\r\nprice is Negotiable', 1, '2023-04-18'),
(21, 47, 32, 'Electronics', 'Sell', 'iphone 14', 1, '2023-04-18'),
(22, 51, 32, 'Electronics', 'Sell', 'iphone 14 ', 1, '2023-04-18'),
(23, 33, 30, 'Accommodation', 'Sell', 'bj', 1, '2023-04-19'),
(24, 33, 30, 'Accommodation', 'Sell', 'dhfjsg fshkfj s', 1, '2023-04-19'),
(25, 33, 30, 'Accommodation', 'Sell', 'jkj hh fk shfls', 1, '2023-04-19'),
(26, 54, 32, 'Electronics', 'Sell', 'iphone14 pro test view', 1, '2023-04-21'),
(27, 55, 32, 'Furniture', 'Sell', 'In good condition.\r\nUsed for 2 years.\r\nPrice 50 dollars only for students\r\nGet your student id at the time we meet.', 1, '2023-04-25');

-- --------------------------------------------------------

--
-- Table structure for table `posts_images`
--

CREATE TABLE `posts_images` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts_images`
--

INSERT INTO `posts_images` (`id`, `post_id`, `image`) VALUES
(1, 17, 'biology.png'),
(2, 17, 'aboutbanner3.png'),
(3, 17, 'aboutbanner2.png'),
(4, 0, 'palaks.jpg'),
(5, 0, 'oranges1.jpg'),
(6, 0, 'palaks1.jpg'),
(7, 0, 'palaks2.jpg'),
(8, 0, 'oranges2.jpg'),
(9, 0, 'brokly1.jpg'),
(10, 0, 'biology1.png'),
(11, 0, 'biology2.png'),
(12, 0, 'biology3.png'),
(13, 18, 'biology4.png'),
(14, 18, 'biology5.png'),
(15, 18, 'biology6.png'),
(16, 19, 'oranges3.jpg'),
(17, 19, 'palaks3.jpg'),
(18, 20, 'download.jpeg'),
(19, 20, 'iphone-14-pro-finish-select-202209-6-7inch-deeppurple_AV1_GEO_US.jpeg'),
(20, 20, 'WhatsApp_Video_2023-04-17_at_7.31.41_PM.mp4'),
(21, 21, 'iphone-14-pro-finish-select-202209-6-7inch-deeppurple_AV1_GEO_US1.jpeg'),
(22, 21, 'download1.jpeg'),
(23, 22, 'iphone-14-pro-finish-select-202209-6-7inch-deeppurple_AV1_GEO_US2.jpeg'),
(24, 22, 'download2.jpeg'),
(25, 23, 'exostic-veg-category.jpg'),
(26, 24, 'milk-category.jpg'),
(27, 25, 'herbs-vegi-category.jpg'),
(28, 26, 'iphone-14-pro-finish-select-202209-6-7inch-deeppurple_AV1_GEO_US3.jpeg'),
(29, 26, 'download3.jpeg'),
(30, 27, 'furniture.jpg'),
(31, 27, 'f2.webp');

-- --------------------------------------------------------

--
-- Table structure for table `posts_spam`
--

CREATE TABLE `posts_spam` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `entry_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts_spam`
--

INSERT INTO `posts_spam` (`id`, `student_id`, `post_id`, `entry_date_time`) VALUES
(1, 25, 17, '2023-04-19 15:29:21'),
(2, 16, 19, '2023-04-19 22:20:00'),
(3, 16, 23, '2023-04-19 22:26:35'),
(4, 16, 24, '2023-04-19 22:27:50'),
(5, 16, 25, '2023-04-19 22:29:08'),
(6, 2, 17, '2023-04-20 00:26:45'),
(7, 2, 18, '2023-04-20 00:31:52'),
(8, 53, 26, '2023-04-21 10:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `age` varchar(20) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `how_many` varchar(250) DEFAULT NULL,
  `Linkedin` varchar(250) NOT NULL,
  `Facebook` varchar(250) NOT NULL,
  `Twitter` varchar(250) NOT NULL,
  `Instagram` varchar(250) NOT NULL,
  `Education_Level` varchar(255) NOT NULL,
  `Food_Type` varchar(255) NOT NULL,
  `Room_Sharing` varchar(255) NOT NULL,
  `Budget` varchar(255) NOT NULL,
  `Hobbies` text NOT NULL,
  `bio` text NOT NULL,
  `profile_pic` varchar(250) NOT NULL DEFAULT 'no_image.png',
  `role` varchar(250) NOT NULL DEFAULT 'student',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `username`, `email`, `phone`, `gender`, `age`, `dob`, `password`, `country_id`, `city_id`, `university_id`, `first_name`, `last_name`, `how_many`, `Linkedin`, `Facebook`, `Twitter`, `Instagram`, `Education_Level`, `Food_Type`, `Room_Sharing`, `Budget`, `Hobbies`, `bio`, `profile_pic`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'brajesh', 'brajesh.vaishnav35@gmail.com', '9098343934', 'Male', '25', '01-Apr-2010', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 1, 'Brajesh', 'Vaishnav', NULL, 'test', 'test', 'test', 'test', 'test', 'test', 'test', '1000', 'test', 'my name is test', 'signin-image.jpg', 'student', 2, '2023-03-20', '0000-00-00'),
(2, 'brajesh1', 'brajesh1@gmail.com', '9098343935', 'male', '25', '1990-01-10', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 1, 'brajesh', 'vaishnav', NULL, 'test', 'test', 'test', 'test', 'Diploma', 'Veg', '2', '222', 'test', 'test', 'no_image.png', 'student', 1, '2023-03-20', '0000-00-00'),
(3, 'manshi', 'manshi@gmail.com', '8269838739', 'female', '25', '2003-01-20', 'e10adc3949ba59abbe56e057f20f883e', 1, 2, 2, 'manshi', 'dixit', NULL, 'test', 'test', 'test', 'test', 'degree', 'Non-Veg', '2', '1200', 'cricket', '', 'no_image.png', 'student', 1, '2023-03-20', '0000-00-00'),
(4, 'Avinash12', 'avi@gmail.com', '8388956621', 'male', '22', '2023-03-23', '9333f7983b47987462531f268e661548', 4, 32, 122, 'Venkata', 'Avinash', NULL, '', '', '', '', 'Undergraduation ', 'Non-Veg', '2', '500', 'Dancing', '', 'no_image.png', 'student', 1, '2023-03-20', '0000-00-00'),
(5, 'Gowtham11', 'gow@gmail.com', '8388956622', 'male', '22', '2023-03-22', '75edfabb700d39439592ce6a97b09b75', 4, 32, 122, 'Ven', 'Gowtham11', NULL, '', '', '', '', 'Undergraduation ', 'Veg', '2', '500', 'Dancing', '', 'no_image.png', 'student', 1, '2023-03-20', '0000-00-00'),
(6, 'yskoushik', 'saikousikyellala@gmail.com', '3464142935', 'male', '23', '2000-03-15', 'e0650e7239a6afabbba77f13a3328309', 4, 32, 122, 'Sai Kousik', 'Yellala', NULL, 'www.linkedin.com', '', '', '', 'degree', 'Veg', '4', '800', 'cricket', '', 'no_image.png', 'student', 1, '2023-03-21', '0000-00-00'),
(7, 'hellotest', 'hellotest@gmail.com', '0798733355', 'male', '30', '2023-03-21', '25d55ad283aa400af464c76d713c07ad', 1, 1, 1, 'Sukhdev', 'Pawar', NULL, '', '', '', '', '', '', '', '', '', '', 'milk-category.jpg', 'student', 1, '2023-03-21', '0000-00-00'),
(8, 'Sukhdev', 'sss@gmail.com', '07987333526', 'male', '35', '2023-03-21', '25d55ad283aa400af464c76d713c07ad', 1, 1, 1, 'Sukhdev', 'Pawar', NULL, '', '', '', '', 'graduation', '', '', '', '', '', 'no_image.png', 'student', 1, '2023-03-21', '0000-00-00'),
(9, 'manish', 'manish@gmail.com', '1231231231', 'male', '30', '2023-02-20', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 1, 'Manish', 'Giri', NULL, 'test', 'test', 'test', 'test', '', 'Veg', '1', '2000', 'cricket', '', 'no_image.png', 'student', 1, '2023-03-22', '0000-00-00'),
(10, 'VMATCHA', '180030070cse@gmail.com', '838895656', 'male', '22', '2020-11-05', '75edfabb700d39439592ce6a97b09b75', 4, 32, 122, 'V', 'M', NULL, 'ts', 'ts', 'ts', 'ts', 'graduation', 'Veg', '2', '1100', '', '', 'no_image.png', 'student', 1, '2023-03-22', '0000-00-00'),
(11, 'hhhhh', 'hhhhh@yopmail.com', '91798600223', 'male', '33', '2023-03-09', '25d55ad283aa400af464c76d713c07ad', 1, 1, 1, 'ffffff', 'fff', NULL, '', '', '', '', '', '', '', '', 'Sssd', 'ddd', 'no_image.png', 'student', 1, '2023-03-22', '0000-00-00'),
(12, 'sai', 'sai@gmail.com', '8388952245', 'male', '20', '2000-11-10', '4e064ef09c057e680b575a90de1628c0', 4, 32, 122, 'sai', 'd', NULL, '', '', '', '', '', 'Non-Veg', '3', '1500', 'Dancing', 'From Hyderabad', 'no_image.png', 'student', 1, '2023-03-24', '0000-00-00'),
(13, 'bargav17', 'boinibargav666@gmail.com', '5186067710', 'male', '22', '1999-12-17', '7878d86a6921ab8ddbe23a57ddaa14d3', 4, 32, 122, 'BARGAV', 'BOINI', NULL, '', '', '', '', 'graduation', 'Non-Veg', '5', '500', 'Cricket ,Browsing net', 'Hi, I\'am Bargav Boini currently doing my master\'s at suny albany', 'no_image.png', 'student', 1, '2023-03-24', '0000-00-00'),
(14, 'Sai kumar', 'saisum51@gmail.com', '5184455725', 'male', '23', '2000-10-26', 'd6ba7d41075e2c5d5f07cd48180205d5', 4, 32, 122, 'Sai kumar reddy', 'Ketham reddy', NULL, '', '', '', '', '', '', '', '', 'Playing cricket and badminton', 'Smile', 'A964F443-6EB4-4993-9251-F33BAC784D971.jpeg', 'student', 1, '2023-03-24', '0000-00-00'),
(15, 'Mahesh', 'mkambala@albany.edu', '5184455817', 'male', '22', '2000-04-24', '482eeb209ab51d3db36757f950fcdda5', 4, 32, 122, 'mahesh', 'kambala', NULL, '', '', '', '', 'graduation', 'Non-Veg', '4', '300', 'Dfs', 'Sdf', 'no_image.png', 'student', 1, '2023-03-25', '0000-00-00'),
(16, 'user1', 'user1@gmail.com', '828387382', 'male', '43', '2023-03-01', '25d55ad283aa400af464c76d713c07ad', 3, 30, 112, 'First', 'Name', NULL, 'linkedin', '', '', '', '', 'Veg', '2', '500', 'Playing football ', 'I am student', 'no_image.png', 'student', 1, '2023-03-27', '0000-00-00'),
(17, 'user2', 'user2@gmail.com', '4902392029', 'male', '33', '2023-03-01', '25d55ad283aa400af464c76d713c07ad', 1, 1, 1, 'second', 'user', NULL, '', '', '', '', '', '', '', '', 'Playing Cricket', 'Student', 'no_image.png', 'student', 1, '2023-03-27', '0000-00-00'),
(18, 'demo1', 'demo1@gmail.com', '7897897895', 'male', '30', '1990-01-01', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 1, 'demo', '1', NULL, 'test', 'test', 'test', '', 'Diploma', 'Veg', '2', '20000', 'test', 'test', 'no_image.png', 'student', 1, '2023-03-27', '0000-00-00'),
(19, 'demo2', 'demo2@gmail.com', '7417417411', 'male', '30', '1990-01-10', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 1, 'demo', '2', NULL, 'test', 'ts', 'test', 'test', '', 'Veg', '2', '40000', 'test', 'test', 'no_image.png', 'student', 1, '2023-03-27', '0000-00-00'),
(20, 'Maheshkmb6 ', 'maheshkmb64@gmail.com', '5184455818', 'male', '22', '1999-06-27', 'c4f24d564a18eef8b5cdfeb0a9c6c083', 4, 32, 122, 'mahesh', 'kambala', NULL, '', '', '', '', '', '', '', '', 'Babab', 'Baba', 'no_image.png', 'student', 1, '2023-03-28', '0000-00-00'),
(21, 'test3', 'test3@gmail.com', '3464142933', 'male', '23', '2023-03-09', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'test', '3.0', NULL, '', '', '', '', '', 'Non-Veg', '4', '300', 'cricket', 'I am es', 'no_image.png', 'student', 1, '2023-03-28', '0000-00-00'),
(22, 'test1', 'test1@gmail.com', '3464142937', 'male', '23', '2011-02-28', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'test', '1', NULL, '', '', '', '', 'graduation', 'Non-Veg', '2', '100', '122', '455', 'no_image.png', 'student', 1, '2023-03-28', '0000-00-00'),
(23, 'test2', 'test2@gmail.com', '3464142938', 'male', '23', '2023-03-27', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'test', '2', NULL, 'anylink.com', 'facebook.com', '', '', 'graduation', 'Non-Veg', '2', '1000', 'cricket', 'I am ...', 'apple.jpeg', 'student', 1, '2023-03-28', '0000-00-00'),
(24, 'test5', 'test5@gmail.com', '3464142989', 'male', '23', '2023-03-10', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'test', '4', NULL, '', '', '', '', '', 'Non-Veg', '2', '1000', '123', '1223', 'no_image.png', 'student', 1, '2023-03-28', '0000-00-00'),
(25, 'seller', '', '', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 0, 'Rajesh', 'sharma', '3', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 2, '2023-03-29', '0000-00-00'),
(26, 'seler', '', '', '', '', '', '25d55ad283aa400af464c76d713c07ad', 1, 2, 0, 'Sukhdev', 'seller', '3', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 2, '2023-04-02', '0000-00-00'),
(27, 'seller2', '', '', '', '', '', '25d55ad283aa400af464c76d713c07ad', 2, 12, 0, 'seller2', 'demo', '4', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 2, '2023-04-02', '0000-00-00'),
(28, 'seller3', 'seller3@gmail.com', '798733526', '', '', '', '25d55ad283aa400af464c76d713c07ad', 2, 14, 0, 'seller3', 'profile', '4', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 1, '2023-04-02', '0000-00-00'),
(29, 'seller1', 'seller1@gmail.com', '12345', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 0, 'Rajesh', 'sharma', '3', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 1, '2023-04-02', '0000-00-00'),
(30, 'seller4', 'seller4@gmail.com', '8374892694', '', '', '', '25d55ad283aa400af464c76d713c07ad', 1, 1, 0, 'seller4', '4', '4', '', '', '', '', '', '', '', '', '', '', 'oranges1.jpg', 'seller', 1, '2023-04-03', '0000-00-00'),
(31, 'pras', 'pras@gmail.com', '8388956626', 'male', '22', '2000-11-15', 'ceb6c970658f31504a901b89dcd3e461', 4, 32, 122, 'pras', 'venkat', NULL, 'https://help.instagram.com/', '', '', '', '', 'Veg', '2', '1000', 'Dancing', 'Graduated from osmania university', 'apple_-_Copy.jpg', 'student', 1, '2023-04-04', '0000-00-00'),
(32, 'mv05', 'mv@gmail.com', '6303554965', '', '', '', 'ceb6c970658f31504a901b89dcd3e461', 4, 32, 0, 'm', 'v', '2', '', '', '', '', '', '', '', '', '', '', 'images_-_Copy.jpg', 'seller', 1, '2023-04-04', '0000-00-00'),
(33, 'sellerdemo', 'demoseller@gmail.com', '66654867868', '', '', '', '25d55ad283aa400af464c76d713c07ad', 3, 30, 0, 'seller', 'demo', '5', '', '', '', '', '', '', '', '', '', '', 'oranges2.jpg', 'seller', 1, '2023-04-05', '0000-00-00'),
(34, 'demouser1', 'demouser1@gmail.com', '9785873566', 'male', '6', '2023-04-05', '25d55ad283aa400af464c76d713c07ad', 3, 30, 112, 'demo', 'user1', NULL, '', '', '', '', 'Diploma', '', '', '', 'cricket', 'I am a tester', 'amul-milk.jpg', 'student', 1, '2023-04-05', '0000-00-00'),
(35, 'dsvv', 'dsvv@gmail.com', '27456244', 'male', '21', '2022-12-07', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'dsvv', 'v', NULL, '', '', '', '', '', '', '', '', 'cricket', 'hh', 'passphoto.jpg', 'student', 1, '2023-04-06', '0000-00-00'),
(36, 'Mb', 'bagava@gmail.com', '9373638272', 'male', '23', '1998-11-06', 'e10adc3949ba59abbe56e057f20f883e', 4, 33, 123, '', '', NULL, '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'student', 1, '2023-04-07', '0000-00-00'),
(37, 'Vk', 'vk@gmail.com', '12479529', 'male', '22', '2023-04-14', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'V', 'K', NULL, '', '', '', '', '', 'Non-Veg', '2', '10000', 'Dance', 'Hi', 'no_image.png', 'student', 1, '2023-04-15', '0000-00-00'),
(38, 'ab', 'ab@gmail.com', '788112551', 'male', '22', '2023-04-20', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'a', 'b', NULL, '', '', '', '', '', 'Veg', '2', '1500', 'da', 'in', 'no_image.png', 'student', 1, '2023-04-15', '0000-00-00'),
(39, 'pq', 'pq@gmail.com', '12347974', 'male', '22', '2023-04-18', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'p', 'q', NULL, '', '', '', '', '', 'Non-Veg', '1', '1021', 'wq', 'asa', 'no_image.png', 'student', 1, '2023-04-15', '0000-00-00'),
(40, '1245689', 'kl@gmail.com', '894651491', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 0, 'k', 'l', '2', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 1, '2023-04-15', '0000-00-00'),
(41, 'wq', '180030770cse@gmail.com', 'dafaf', '', '', '', 'fcea920f7412b5da7be0cf42b8c93759', 4, 32, 0, 'MATCHA', 'GOWTHAM', '2', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 1, '2023-04-15', '0000-00-00'),
(42, 'svvd', 'svvd@gmail.com', '12367902', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 0, 'svv', 'd', '10', '', '', '', '', '', '', '', '', '', '', 'images.png', 'seller', 1, '2023-04-18', '0000-00-00'),
(43, 'ui', 'ui@gmail.com', '899654819554', 'male', '22', '2023-04-17', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'u', 'i', NULL, '', '', '', '', '', 'Veg', '3', '10000', 'Dancing ', 'hi hello', 'DSC_14141.JPG', 'student', 1, '2023-04-18', '0000-00-00'),
(44, 'mvg', 'mvg@gmail.com', '8985684128', 'male', '22', '2000-11-05', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'mv', 'g', NULL, '', '', '', '', 'graduation', 'Veg', '2', '1000', 'hiking', 'Grad student admitted fall', 'grape2.jpg', 'student', 1, '2023-04-18', '0000-00-00'),
(45, 'qw', 'qw@gmail.com', '78984687168', 'male', '22', '2000-11-05', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'q', 'w', NULL, '', '', '', '', '', 'Veg', '3', '456', 'jogging', 'grad student spring', 'DSC_1398.JPG', 'student', 1, '2023-04-18', '0000-00-00'),
(46, 'tui', 'tu@gmail.com', '569845165', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 0, 't', 'u', '4', '', '', '', '', '', '', '', '', '', '', 'DSC_140325.JPG', 'seller', 1, '2023-04-18', '0000-00-00'),
(47, 'koushiky', 'saikoushikkumar@gmail.com', '8388952246', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 0, 'Koushik sai', 'kumar', '5', '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'seller', 1, '2023-04-18', '0000-00-00'),
(48, 'Vikram', 'vikram@gmail.com', '8988952215', 'male', '22', '2000-06-06', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'vikram', 'nandhuri', NULL, '', '', '', '', 'Undergraduation ', 'Veg', '2', '1000', 'Hiking\r\ndancing', 'Graduated from Klu.\r\nFrom hyderabad \r\nfoodie', 'WhatsApp_Image_2023-03-17_at_23.16.27.jpg', 'student', 1, '2023-04-18', '0000-00-00'),
(49, 'rahul', 'rahul@gmail.com', '9866806853', 'male', '20', '2000-11-05', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 122, 'rahul', 'varma', NULL, '', '', '', '', '', '', '', '', 'dacing', 'hiki', 'no_image.png', 'student', 1, '2023-04-18', '0000-00-00'),
(50, 'Anilkumar', 'anil@gmail.com', '8790906637', 'male', '20', '2023-03-30', '25d55ad283aa400af464c76d713c07ad', 4, 32, 122, 'anil', 'k', NULL, '', 'facebook.com', '', '', 'graduation', 'Veg', '4', '1000', 'Hiking,\r\nDancing', 'Grad student Fall\r\nfrom india', 'propic1.jpg', 'student', 1, '2023-04-18', '0000-00-00'),
(51, 'ajayk', 'ajay@gmail.com', '838895667', '', '', '', '25d55ad283aa400af464c76d713c07ad', 4, 32, 0, 'ajay', 'k', '5', '', '', '', '', '', '', '', '', '', '', 'propic11.jpg', 'seller', 1, '2023-04-18', '0000-00-00'),
(52, 's1', 's1@gmail.com', '4849645', 'male', '23', '2023-04-23', '25d55ad283aa400af464c76d713c07ad', 4, 32, 122, '', '', NULL, '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'student', 1, '2023-04-21', '0000-00-00'),
(53, 'Madhavi', 'madhavi@gmail.com', '9866806859', 'male', '22', '2000-11-05', '25d55ad283aa400af464c76d713c07ad', 4, 32, 122, 'Madhavi', 'Latha', NULL, '', 'https://en-gb.facebook.com/people/Rakesh-Reddy/pfbid02UFxDRATHbCKA9YcSucDG3CJrkccGD7y2t3ToAGNWxQxCUpvPeuJj1oobsXbxHvHtl/', '', '', 'graduation', 'Veg', '3', '1000', 'Dancing\r\nHiking', 'Graduated from the year 2022\r\nExcited to meet and make new Friends.', 'SAVE_20220105_145006_(1)-01-01.jpeg', 'student', 1, '2023-04-21', '0000-00-00'),
(54, 'uiop', 'uiop@gmail.com', '89642862469', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 0, 'ui', 'op', '8', '', '', '', '', '', '', '', '', '', '', 'DSC_13981.JPG', 'seller', 1, '2023-04-21', '0000-00-00'),
(55, 'pl', 'pl@gmail.com', '8688955863', '', '', '', 'e10adc3949ba59abbe56e057f20f883e', 4, 32, 0, 'pl', 'l', '5', '', '', '', '', '', '', '', '', '', '', 'SAVE_20220105_145006_(1)-01-012.jpeg', 'seller', 1, '2023-04-25', '0000-00-00'),
(56, 'ttttt', 'ttttt@yopmail.com', '677575896', 'male', '55', '2023-04-26', '25d55ad283aa400af464c76d713c07ad', 4, 32, 122, 'Teset', 'usergfh', NULL, 'vhhg', 'gfh', 'g', 'gf', '', 'Non-Veg', '3', '500', 'nothing', 'nothing v', 'no_image.png', 'student', 1, '2023-04-26', '0000-00-00'),
(57, 't1', 't1@gmail.com', '9639639633', 'male', '30', '2023-01-01', '25d55ad283aa400af464c76d713c07ad', 1, 1, 1, 'test', 'test', NULL, 'test', 'test', 'test', 'test', '', 'Veg', '1', '2500', 'test', 'test', 'no_image.png', 'student', 1, '2023-04-27', '0000-00-00'),
(58, 'newuser1', 'newuser@yomail.com', '1234374', 'male', '44', '2023-04-27', '25d55ad283aa400af464c76d713c07ad', 1, 4, 10, '', '', NULL, '', '', '', '', '', '', '', '', '', '', 'no_image.png', 'student', 1, '2023-04-27', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `support_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support`
--

INSERT INTO `support` (`support_id`, `first_name`, `last_name`, `country`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Rizwan', 'Khan', 'India', 'Suuport', '2023-03-01', '0000-00-00'),
(2, 'Rizwan', 'Khan', 'India', 'Suuport', '2023-03-01', '0000-00-00'),
(3, 'Rizwan', 'Khan', 'India', 'Suuport', '2023-03-01', '0000-00-00'),
(4, 'Rizwan', 'Khan', 'India', 'Suuport', '2023-03-16', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `trip_from` varchar(250) NOT NULL,
  `trip_to` varchar(250) NOT NULL,
  `trip_date` date NOT NULL,
  `trip_time` time NOT NULL,
  `no_of_passanger` int(11) NOT NULL,
  `smoker` varchar(50) NOT NULL,
  `licend` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `notes` text NOT NULL,
  `entry_date` date NOT NULL,
  `entry_date_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`trip_id`, `student_id`, `trip_from`, `trip_to`, `trip_date`, `trip_time`, `no_of_passanger`, `smoker`, `licend`, `description`, `notes`, `entry_date`, `entry_date_time`) VALUES
(1, 1, '1', '1', '2023-04-01', '15:10:10', 5, 'Yes', '1 year', 'test', 'test', '2023-03-18', '2023-03-18 11:14:13'),
(2, 11, '1', '1', '2023-03-08', '01:09:00', 10, 'Smoker', '2 year', 'Nothing', 'Hello', '2023-03-23', '2023-03-23 16:57:34'),
(3, 12, '1', '1', '2023-03-14', '12:56:00', 4, 'non smoker', '2 year', 'Black honda suv', 'Hi Hello', '2023-03-24', '2023-03-24 16:55:35'),
(4, 13, '1', '1', '2023-03-25', '18:00:00', 3, 'non smoker', '1 year', 'mercedes benz', 'lets catch up at 5:30pm', '2023-03-24', '2023-03-24 16:59:52'),
(5, 7, '1', '1', '2023-03-09', '22:04:00', 4, '4', '1 year', '4', '444', '2023-03-26', '2023-03-26 16:33:25'),
(7, 7, '3', '2', '2023-03-03', '19:26:00', 3, 'Non-Smoker', '3 year', 'Breza', 'Call me when you see', '2023-03-27', '2023-03-27 13:53:48'),
(8, 16, '1', '1', '2023-03-28', '09:53:00', 3, 'Smoker', '1 year', 'test', '33333333', '2023-03-27', '2023-03-27 15:22:35'),
(9, 17, '1', '1', '2023-03-28', '20:54:00', 6, 'Smoker', '2 year', 'hhh', 'Cbgffgh', '2023-03-27', '2023-03-27 15:23:32'),
(10, 18, '1', '1', '2023-03-30', '22:42:00', 5, 'Smoker', '1 year', 'test', 'testttt', '2023-03-27', '2023-03-27 17:11:18'),
(11, 6, '32', '33', '2023-03-30', '00:28:00', 4, 'Smoker', '2 year', 'Black Dodge Challenger', 'Wait at Colonie', '2023-03-28', '2023-03-28 01:26:13'),
(12, 23, '32', '34', '2023-03-16', '01:53:00', 3, 'Non-Smoker', '3 year', 'White car', 'Be on time', '2023-03-28', '2023-03-28 03:52:20'),
(13, 49, '32', '34', '2023-04-11', '22:00:00', 4, 'Smoker', '2 year', 'Kia sonet', 'Wait at  colins circle', '2023-04-18', '2023-04-18 03:51:01'),
(14, 50, '32', '35', '2023-04-17', '10:50:00', 5, 'Non-Smoker', '2 year', 'BMW Modelz', 'Be on time.\r\nWait at colins circle.', '2023-04-18', '2023-04-18 04:48:27'),
(15, 44, '32', '33', '2023-04-11', '10:00:00', 3, 'Non-Smoker', '3 year', 'White tesla Model x NBk9988 number plate', 'Wait at right side of student quad block', '2023-04-21', '2023-04-21 04:31:45');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `university_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`university_id`, `city_id`, `name`) VALUES
(1, 1, 'charles sturt university'),
(2, 2, 'steve institute of technology'),
(3, 2, 'emerson colleges'),
(4, 2, 'arcaida university'),
(5, 2, 'guilford university'),
(6, 3, 'university of wollongong '),
(7, 3, 'nan tien institute'),
(8, 3, 'richard johnson college'),
(9, 3, 'weerona college'),
(10, 4, 'griffith univeristy'),
(11, 4, 'bond university'),
(12, 4, 'southern cross university'),
(13, 5, 'the university of western australia'),
(14, 5, 'curtin university'),
(15, 5, 'edith cowan university'),
(16, 5, 'murdoch university'),
(17, 6, 'the university of adelaide'),
(18, 6, 'university of south australia'),
(19, 6, 'flinders university'),
(20, 6, 'carnegie mellon university'),
(21, 7, 'the australian national university'),
(22, 7, 'university of canberra'),
(23, 7, 'canberra institute of technology'),
(24, 7, 'royal military college'),
(25, 8, 'the university of sydney'),
(26, 8, 'UNSW sydney'),
(27, 8, 'university of technology'),
(28, 8, 'macquarie university'),
(29, 9, 'the university of queensland'),
(30, 9, 'QUT gardens point campus'),
(31, 9, 'shafston international college'),
(32, 9, 'australian catholic university'),
(33, 10, 'university of melborne'),
(34, 10, 'monash university clayton campus'),
(35, 10, 'victoria university'),
(36, 10, 'la trobe university'),
(37, 11, 'Albany College of Pharmacy and Health Sciences'),
(38, 11, 'The College of Saint Rose'),
(39, 11, 'suny albany'),
(40, 12, 'New York University'),
(41, 12, 'baruch university'),
(42, 12, 'barnand college'),
(43, 12, 'barnand college'),
(44, 13, 'montclair state university'),
(45, 13, 'the college of new jersey'),
(46, 13, 'rawan university'),
(47, 13, 'rawan university'),
(48, 14, 'stanford university'),
(49, 14, 'university of california,berkeley'),
(50, 14, 'university of california, los angeles'),
(51, 14, 'university of california, los angeles'),
(52, 14, 'university of southern california'),
(53, 15, 'loyola university'),
(54, 15, 'the university of chicago'),
(55, 15, 'university of illinois'),
(56, 15, 'university of illinois'),
(57, 16, 'boston college'),
(58, 16, 'harvard university'),
(59, 16, 'tufts university'),
(60, 16, 'tufts university'),
(61, 17, 'university of pennsylvania'),
(62, 17, 'drexel university'),
(63, 17, 'temple university'),
(64, 17, 'temple university'),
(65, 18, 'university of lynchburg'),
(66, 18, 'liberty university'),
(67, 18, 'central virginia university college'),
(68, 18, 'central virginia university college'),
(69, 18, 'randolph college'),
(70, 19, 'universities of wisconsion'),
(71, 19, 'edewood college'),
(72, 19, 'madison area technical college'),
(73, 19, 'madison area technical college'),
(74, 19, 'herzing university'),
(75, 20, 'university of south carolina'),
(76, 20, ' columbia college'),
(77, 20, 'benedict college'),
(78, 21, 'the universoty of tampa'),
(79, 21, 'university of south florida'),
(80, 21, 'florida college'),
(81, 22, 'southern methodist university'),
(82, 22, 'the university of texas at dallas'),
(83, 22, 'university of dallas'),
(84, 23, 'university of brighton'),
(85, 23, 'university of sussex'),
(86, 23, 'brighton metropolitan college'),
(87, 23, 'BIMM brighton'),
(88, 24, 'newcastle university'),
(89, 24, 'newcastle college-rye hill campus'),
(90, 24, 'northumbria university newcastle'),
(91, 24, 'kenton technology university'),
(92, 25, 'university of aberdeen'),
(93, 25, 'robert gordon university'),
(94, 25, 'north east scotland college'),
(95, 25, 'marischal college'),
(96, 26, 'ersity of birmingham'),
(97, 26, 'birmingham city university'),
(98, 26, 'aston university'),
(99, 26, 'sutton coldfield college'),
(100, 27, 'nottingham trent university'),
(101, 27, 'university of nottingham'),
(102, 27, 'nottingham business school'),
(103, 27, 'st john`s college nottingham'),
(104, 28, 'coventry univerisity'),
(105, 28, 'university of warwick'),
(106, 28, 'coventry college'),
(107, 28, 'cu coventry'),
(108, 29, 'university of glasgow'),
(109, 29, 'glasgow caledonian university'),
(110, 29, 'city of glassgow college'),
(111, 29, 'university of strathclyde'),
(112, 30, 'the university of manchester'),
(113, 30, 'the manchester college'),
(114, 30, 'RNCM'),
(115, 30, 'victoria university of manchester'),
(116, 31, 'university of london'),
(117, 31, 'imperial college london'),
(118, 31, 'university of west london'),
(119, 31, 'university of greenwich'),
(120, 32, 'Albany College of Pharmacy and Health Sciences'),
(121, 32, 'The College of Saint Rose'),
(122, 32, 'suny albany'),
(123, 33, 'New York University'),
(124, 33, 'baruch university'),
(125, 33, 'barnand college'),
(126, 33, 'barnand college'),
(127, 34, 'montclair state university'),
(128, 34, 'the college of new jersey'),
(129, 34, 'rawan university'),
(130, 35, 'stanford university'),
(131, 35, 'university of california,berkeley'),
(132, 35, 'university of california, los angeles'),
(133, 35, 'university of southern california'),
(134, 36, 'loyola university'),
(135, 36, 'the university of chicago'),
(136, 36, 'university of illinois'),
(137, 37, 'boston college'),
(138, 37, 'harvard university'),
(139, 37, 'tufts university'),
(140, 38, 'university of pennsylvania'),
(141, 38, 'drexel university'),
(142, 38, 'temple university'),
(143, 39, 'university of lynchburg'),
(144, 39, 'liberty university'),
(145, 39, 'central virginia university college'),
(146, 39, 'randolph college'),
(147, 40, 'universities of wisconsion'),
(148, 40, 'edewood college'),
(149, 40, 'madison area technical college'),
(150, 40, 'herzing university'),
(151, 41, 'university of south carolina'),
(152, 41, 'columbia college'),
(153, 41, 'benedict college'),
(154, 42, 'the universoty of tampa'),
(155, 42, 'university of south florida'),
(156, 42, 'florida college'),
(157, 43, 'southern methodist university'),
(158, 43, 'the university of texas at dallas'),
(159, 43, 'university of dallas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `carpool`
--
ALTER TABLE `carpool`
  ADD PRIMARY KEY (`carpool_id`);

--
-- Indexes for table `carpool_join`
--
ALTER TABLE `carpool_join`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_request`
--
ALTER TABLE `message_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `posts_images`
--
ALTER TABLE `posts_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts_spam`
--
ALTER TABLE `posts_spam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`support_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`university_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `carpool`
--
ALTER TABLE `carpool`
  MODIFY `carpool_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `carpool_join`
--
ALTER TABLE `carpool_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `message_request`
--
ALTER TABLE `message_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `posts_images`
--
ALTER TABLE `posts_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `posts_spam`
--
ALTER TABLE `posts_spam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `support_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `university_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
