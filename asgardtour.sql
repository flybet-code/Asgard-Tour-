-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2025 at 03:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asgardtour`a
--

-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `asgardtour`;
USE `asgardtour`;
CREATE TABLE `blogs` (
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blog_id`, `user_id`, `title`, `content`, `location`, `likes`, `created_at`, `image`) VALUES
(1, 1, 'My Iceland Adventure', 'A trip of a lifetime in Iceland!', 'Iceland', 120, '2025-03-26 09:03:15', NULL),
(2, 2, 'Exploring the Fjords', 'The Norwegian fjords are breathtaking!', 'Norway', 95, '2025-03-26 09:03:15', NULL),
(3, 3, 'Safari Experience', 'Seeing lions up close was unreal!', 'Kenya', 150, '2025-03-26 09:03:15', NULL),
(4, 4, 'Great Wall Hike', 'A challenging but rewarding hike.', 'China', 85, '2025-03-26 09:03:15', NULL),
(5, 5, 'Santorini Escape', 'The views were unforgettable.', 'Greece', 130, '2025-03-26 09:03:15', NULL),
(6, 6, 'Tokyo Trip', 'The perfect blend of tradition and modernity.', 'Japan', 110, '2025-03-26 09:03:15', NULL),
(7, 7, 'Grand Canyon Hike', 'The canyon’s beauty is unmatched.', 'USA', 90, '2025-03-26 09:03:15', NULL),
(68, 94, 'Exploring Addis Ababa', 'A day spent in the capital city, tasting local cuisines.', 'Addis Ababa', 50, '2025-04-02 00:39:04', NULL),
(69, 95, 'The Beauty of Lalibela', 'Visiting the rock-hewn churches, a marvel of architecture.', 'Lalibela', 75, '2025-04-02 00:39:04', NULL),
(70, 96, 'Trekking in the Simien Mountains', 'An unforgettable adventure in the African highlands.', 'Simien Mountains', 120, '2025-04-02 00:39:04', NULL),
(71, 97, 'Cultural Experiences in Omo Valley', 'Diving into the rich cultures of Southern Ethiopia.', 'Omo Valley', 40, '2025-04-02 00:39:04', NULL),
(72, 98, 'Dancing with the Tribes', 'Experiencing the festivities with the local tribes.', 'Omo Valley', 80, '2025-04-02 00:39:04', NULL),
(73, 99, 'Danakil Depression Wonders', 'Witnessing some of the hottest spots on Earth.', 'Danakil Depression', 90, '2025-04-02 00:39:04', NULL),
(74, 100, 'Safaris in Awash National Park', 'Encountering unique wildlife.', 'Awash', 60, '2025-04-02 00:39:04', NULL),
(75, 101, 'The Festival of Harar', 'Celebrating in one of the oldest towns of Ethiopia.', 'Harar', 55, '2025-04-02 00:39:04', NULL),
(76, 102, 'Bale Mountains Wildlife', 'Exploring the unique flora and fauna.', 'Bale Mountains', 30, '2025-04-02 00:39:04', NULL),
(77, 103, 'Unforgettable Blue Nile Falls', 'A trip to the majestic Blue Nile Waterfalls.', 'Blue Nile', 95, '2025-04-02 00:39:04', NULL),
(78, 104, 'A Glimpse of Ethiopian Cuisine', 'Tasting doro wat and injera for the first time.', 'Addis Ababa', 100, '2025-04-02 00:39:04', NULL),
(79, 105, 'Exploring the History of Gonder', 'A journey through the castles of Gonder.', 'Gonder', 85, '2025-04-02 00:39:04', NULL),
(80, 106, 'The Rich History of Aksum', 'Discovering ancient Ethiopia.', 'Aksum', 70, '2025-04-02 00:39:04', NULL),
(81, 107, 'Cultural Heritage of Harar', 'The city of Awadhi cuisine and colorful markets.', 'Harar', 100, '2025-04-02 00:39:04', NULL),
(82, 108, 'The Beauty of Lake Tana', 'Exploring the islands and monasteries.', 'Lake Tana', 65, '2025-04-02 00:39:04', NULL),
(83, 109, 'Wildlife in Kafa Biosphere', 'Trekking in the coffee-producing areas.', 'Kafa', 20, '2025-04-02 00:39:04', NULL),
(84, 110, 'Nature in the Gurage Region', 'Experience the greenery and diversity.', 'Gurage', 45, '2025-04-02 00:39:04', NULL),
(85, 111, 'Sailing on Lake Ziway', 'A refreshing experience on another beautiful lake.', 'Lake Ziway', 30, '2025-04-02 00:39:04', NULL);

--
-- Triggers `blogs`
--
DELIMITER $$
CREATE TRIGGER `after_blog_insert` AFTER INSERT ON `blogs` FOR EACH ROW BEGIN
    INSERT INTO notifications (user_id, message, type, created_at, status)
    VALUES (NEW.user_id, CONCAT('A new blog titled "', NEW.title, '" has been posted.'), 'Review', CURRENT_TIMESTAMP, 'unread');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Confirmed','Pending','Cancelled') DEFAULT 'Pending',
  `booking_day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `tour_id`, `booking_date`, `status`, `booking_day`) VALUES
(1, 1, 1, '2025-03-26 08:58:30', 'Confirmed', '2025-03-26'),
(2, 1, 2, '2025-03-26 08:58:30', 'Confirmed', '2025-03-26'),
(3, 1, 1, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(4, 1, 5, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(5, 2, 3, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(6, 2, 6, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(7, 3, 2, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(8, 4, 7, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(9, 5, 8, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(10, 5, 10, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(11, 6, 9, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(12, 7, 4, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(13, 7, 12, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(14, 8, 11, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(15, 9, 13, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(16, 10, 14, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(17, 10, 16, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(18, 11, 15, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(19, 12, 17, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(20, 12, 19, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(21, 13, 18, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(22, 14, 20, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(23, 15, 1, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(24, 15, 3, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(25, 15, 5, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(26, 2, 7, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(27, 6, 9, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(28, 10, 14, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(29, 14, 20, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(30, 11, 15, '2025-03-26 09:02:33', 'Confirmed', '2025-03-26'),
(31, 1, 5, '2025-03-20 07:30:00', 'Confirmed', '2025-03-20'),
(32, 2, 8, '2025-03-21 11:15:00', 'Confirmed', '2025-03-21'),
(33, 3, 12, '2025-03-22 05:45:00', 'Pending', '2025-03-22'),
(34, 4, 6, '2025-03-23 14:30:00', 'Confirmed', '2025-03-23'),
(35, 5, 15, '2025-03-24 09:00:00', 'Cancelled', '2025-03-24'),
(36, 6, 10, '2025-03-25 06:00:00', 'Confirmed', '2025-03-25'),
(37, 7, 20, '2025-03-26 13:45:00', 'Confirmed', '2025-03-26'),
(38, 47, 7, '2025-04-02 03:15:52', 'Pending', '2025-04-02'),
(39, 1, 1, '2025-04-10 14:34:30', 'Pending', NULL),
(40, 2, 1, '2025-04-10 14:34:30', 'Pending', NULL),
(41, 3, 2, '2025-04-10 14:34:30', 'Pending', NULL),
(42, 4, 2, '2025-04-10 14:34:30', 'Pending', NULL),
(43, 5, 3, '2025-04-10 14:34:30', 'Pending', NULL),
(44, 6, 3, '2025-04-10 14:34:30', 'Pending', NULL),
(45, 7, 4, '2025-04-10 14:34:30', 'Pending', NULL),
(46, 8, 4, '2025-04-10 14:34:30', 'Pending', NULL),
(47, 9, 5, '2025-04-10 14:34:30', 'Pending', NULL),
(48, 10, 5, '2025-04-10 14:34:30', 'Pending', NULL);

--
-- Triggers `bookings`
--
DELIMITER $$
CREATE TRIGGER `after_booking_insert` AFTER INSERT ON `bookings` FOR EACH ROW BEGIN
    INSERT INTO notifications (user_id, message, type, created_at, status)
    VALUES (NEW.user_id, CONCAT('Your booking for tour ', NEW.tour_id, ' has been confirmed.'), 'Booking', CURRENT_TIMESTAMP, 'unread');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `galleryimages`
--

CREATE TABLE `galleryimages` (
  `gallery_image_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `galleryimages`
--

INSERT INTO `galleryimages` (`gallery_image_id`, `tour_id`, `image_url`, `uploaded_at`) VALUES
(1, 59, 'uploads/ERMICH/gallery/1743772485_piclumen-1741042865150.png', '2025-04-04 13:14:45'),
(2, 59, 'uploads/ERMICH/gallery/1743772485_piclumen-1741042887774.png', '2025-04-04 13:14:45'),
(3, 59, 'uploads/ERMICH/gallery/1743772485_piclumen-1741042953523.png', '2025-04-04 13:14:45'),
(4, 59, 'uploads/ERMICH/gallery/1743772485_piclumen-1741042973145.png', '2025-04-04 13:14:45'),
(5, 59, 'uploads/ERMICH/gallery/1743772485_piclumen-1741042985238.png', '2025-04-04 13:14:45'),
(6, 59, 'uploads/ERMICH/gallery/1743772485_piclumen-1741043063089.png', '2025-04-04 13:14:45'),
(7, 60, 'uploads/ERMICH/gallery/1743772773_piclumen-1741042865150.png', '2025-04-04 13:19:33'),
(8, 60, 'uploads/ERMICH/gallery/1743772773_piclumen-1741042887774.png', '2025-04-04 13:19:33'),
(9, 60, 'uploads/ERMICH/gallery/1743772773_piclumen-1741042953523.png', '2025-04-04 13:19:33'),
(10, 60, 'uploads/ERMICH/gallery/1743772773_piclumen-1741042973145.png', '2025-04-04 13:19:33'),
(11, 60, 'uploads/ERMICH/gallery/1743772773_piclumen-1741042985238.png', '2025-04-04 13:19:33'),
(12, 60, 'uploads/ERMICH/gallery/1743772773_piclumen-1741043063089.png', '2025-04-04 13:19:33'),
(13, 61, 'uploads/SHEKA/gallery/1743772851_piclumen-1741043200870.png', '2025-04-04 13:20:51'),
(14, 62, 'uploads/SHEKAtt/gallery/1743773227_piclumen-1741043200870.png', '2025-04-04 13:27:07'),
(15, 62, 'uploads/SHEKAtt/gallery/1743773227_piclumen-1741043257343.png', '2025-04-04 13:27:07'),
(16, 62, 'uploads/SHEKAtt/gallery/1743773227_piclumen-1741043326363.png', '2025-04-04 13:27:07'),
(17, 62, 'uploads/SHEKAtt/gallery/1743773227_piclumen-1741043475348.png', '2025-04-04 13:27:07'),
(18, 62, 'uploads/SHEKAtt/gallery/1743773227_piclumen-1741043482244.png', '2025-04-04 13:27:07'),
(19, 63, 'uploads/SHEKA/gallery/1743773276_piclumen-1741043200870.png', '2025-04-04 13:27:56'),
(20, 63, 'uploads/SHEKA/gallery/1743773276_piclumen-1741043257343.png', '2025-04-04 13:27:56'),
(21, 63, 'uploads/SHEKA/gallery/1743773276_piclumen-1741043326363.png', '2025-04-04 13:27:56'),
(22, 64, 'uploads/MESSI/gallery/1743773934_piclumen-1741043257343.png', '2025-04-04 13:38:54'),
(23, 64, 'uploads/MESSI/gallery/1743773934_piclumen-1741043326363.png', '2025-04-04 13:38:54'),
(24, 64, 'uploads/MESSI/gallery/1743773934_piclumen-1741043475348.png', '2025-04-04 13:38:54'),
(25, 64, 'uploads/MESSI/gallery/1743773934_piclumen-1741043482244.png', '2025-04-04 13:38:54'),
(26, 64, 'uploads/MESSI/gallery/1743773934_piclumen-1741043535787.png', '2025-04-04 13:38:54'),
(27, 65, 'uploads/brentford/gallery/1743790818_piclumen-1741043200870.png', '2025-04-04 18:20:18'),
(28, 65, 'uploads/brentford/gallery/1743790818_piclumen-1741043257343.png', '2025-04-04 18:20:18'),
(29, 65, 'uploads/brentford/gallery/1743790818_piclumen-1741043326363.png', '2025-04-04 18:20:18'),
(30, 65, 'uploads/brentford/gallery/1743790818_piclumen-1741043475348.png', '2025-04-04 18:20:18'),
(31, 66, 'uploads/brentford/gallery/1743791338_piclumen-1741043200870.png', '2025-04-04 18:28:58'),
(32, 66, 'uploads/brentford/gallery/1743791338_piclumen-1741043257343.png', '2025-04-04 18:28:58'),
(33, 66, 'uploads/brentford/gallery/1743791338_piclumen-1741043326363.png', '2025-04-04 18:28:58'),
(34, 66, 'uploads/brentford/gallery/1743791338_piclumen-1741043475348.png', '2025-04-04 18:28:58'),
(35, 67, 'uploads/yihunn/gallery/1743796020_alexander-slattery-LI748t0BK8w-unsplash.jpg', '2025-04-04 19:47:00'),
(36, 67, 'uploads/yihunn/gallery/1743796020_greg-rakozy-0LU4vO5iFpM-unsplash.jpg', '2025-04-04 19:47:00'),
(37, 67, 'uploads/yihunn/gallery/1743796020_jms-kFHz9Xh3PPU-unsplash.jpg', '2025-04-04 19:47:00'),
(38, 68, 'uploads/stamford/gallery/1743796102_images.jpg', '2025-04-04 19:48:22'),
(39, 68, 'uploads/stamford/gallery/1743796102_20250404145821.jpg', '2025-04-04 19:48:22'),
(40, 68, 'uploads/stamford/gallery/1743796102_20250404145818.jpg', '2025-04-04 19:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `multimedia`
--

CREATE TABLE `multimedia` (
  `media_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `media_type` enum('Image','Video') NOT NULL,
  `media_url` text NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `subscriber_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `type` enum('Booking','Review','Milestone','Alert') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('read','unread') NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `type`, `created_at`, `status`) VALUES
(1, 1, 'Your booking for tour 1 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(2, 2, 'Your booking for tour 1 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(3, 3, 'Your booking for tour 2 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(4, 4, 'Your booking for tour 2 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(5, 5, 'Your booking for tour 3 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(6, 6, 'Your booking for tour 3 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(7, 7, 'Your booking for tour 4 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(8, 8, 'Your booking for tour 4 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(9, 9, 'Your booking for tour 5 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread'),
(10, 10, 'Your booking for tour 5 has been confirmed.', 'Booking', '2025-04-10 14:34:30', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `tour_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Amazing tour!', '2025-03-26 09:02:56'),
(2, 2, 1, 4, 'Great experience!', '2025-03-26 09:02:56'),
(3, 3, 2, 5, 'Loved every moment!', '2025-03-26 09:02:56'),
(4, 4, 2, 5, 'Breathtaking views!', '2025-03-26 09:02:56'),
(5, 5, 3, 4, 'Good but could be better.', '2025-03-26 09:02:56'),
(6, 6, 3, 5, 'Fantastic!', '2025-03-26 09:02:56'),
(7, 7, 4, 5, 'Once in a lifetime trip!', '2025-03-26 09:02:56'),
(8, 8, 4, 5, 'Incredible!', '2025-03-26 09:02:56'),
(9, 9, 5, 4, 'Nice experience!', '2025-03-26 09:02:56'),
(10, 10, 5, 5, 'Best trip ever!', '2025-03-26 09:02:56'),
(11, 11, 6, 4, 'Enjoyed it!', '2025-03-26 09:02:56'),
(12, 12, 6, 5, 'Highly recommended!', '2025-03-26 09:02:56'),
(13, 13, 7, 5, 'Beautiful and fun!', '2025-03-26 09:02:56'),
(14, 14, 7, 5, 'Loved the hiking!', '2025-03-26 09:02:56'),
(15, 15, 8, 5, 'Perfect tour!', '2025-03-26 09:02:56'),
(16, 1, 8, 4, 'Would go again!', '2025-03-26 09:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `tour_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `featured` tinyint(1) DEFAULT 0,
  `recommended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`tour_id`, `title`, `description`, `price`, `start_date`, `end_date`, `location`, `main_image`, `video_url`, `created_at`, `status`, `featured`, `recommended`) VALUES
(1, 'Iceland Adventure', 'A 5-day tour exploring Iceland.', 1500.00, '2025-06-01', '2025-06-05', 'Iceland', NULL, NULL, '2025-03-26 08:57:43', 'active', 0, 0),
(2, 'Norwegian Fjords', 'A scenic cruise through Norway’s fjords.', 2200.00, '2025-07-10', '2025-07-15', 'Norway', NULL, NULL, '2025-03-26 08:57:43', 'active', 0, 0),
(3, 'Iceland Adventure', 'A 5-day tour exploring Iceland.', 1500.00, '2025-06-01', '2025-06-05', 'Iceland', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(4, 'Norwegian Fjords', 'A scenic cruise through Norway’s fjords.', 2200.00, '2025-07-10', '2025-07-15', 'Norway', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(5, 'African Safari', 'Experience the wildlife of Africa.', 3200.00, '2025-08-05', '2025-08-15', 'Kenya', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(6, 'Great Wall of China', 'Walk along the Great Wall.', 1800.00, '2025-09-01', '2025-09-07', 'China', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(7, 'Santorini Escape', 'A romantic getaway to Greece.In your database schema, the following tables are designed to store multiple images and videos related to tours:\r\n\r\n1. **`galleryimages`**: This table is specifically for storing images related to tours. Each entry corresponds to a single image associated with a specific `tour_id`. Therefore, you can have multiple entries in this table for different images of the same tour.\r\n\r\n   - **Columns**:\r\n     - `gallery_image_id`: Unique identifier for each image.\r\n     - `tour_id`: Foreign key referencing the tour the image is associated with.\r\n     - `image_url`: URL of the image.\r\n     - `uploaded_at`: Timestamp when the image was uploaded.\r\n\r\n2. **`multimedia`**: This table is used to store both images and videos related to tours. The use of the `media_type` column allows you to specify whether each entry is an image or a video. As with the `galleryimages` table, you can have multiple entries for the same `tour_id`.\r\n\r\n   - **Columns**:\r\n     - `media_id`: Unique identifier for each media entry.\r\n     - `tour_id`: Foreign key referencing the tour the media is associated with.\r\n     - `media_type`: Specifies the type of media (either \'Image\' or \'Video\').\r\n     - `media_url`: URL of the media.\r\n     - `uploaded_at`: Timestamp when the media was uploaded.\r\n\r\n3. **`videogallery`**: This table is dedicated to storing videos related to tours. Each entry corresponds to a single video associated with a specific `tour_id`. Thus, you can also have multiple video entries for the same tour.\r\n\r\n   - **Columns**:\r\n     - `gallery_video_id`: Unique identifier for each video.\r\n     - `tour_id`: Foreign key referencing the tour the video is associated with.\r\n     - `video_url`: URL of the video.\r\n     - `description`: Optional description of the video.\r\n     - `uploaded_at`: Timestamp when the video was uploaded.\r\n\r\n### Summary:\r\n- **Multiple Images**: Stored in the `galleryimages` table and `multimedia` table (if they are categorized as images).\r\n- **Multiple Videos**: Stored in the `multimedia` table (if categorized as videos) and the `videogallery` table. \r\n\r\nThis setup allows for flexible management of different types of media related to tours in your database.', 2500.00, '2025-10-10', '2025-10-15', 'Greece', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(8, 'Amazon Rainforest', 'A nature expedition in Brazil.', 2000.00, '2025-11-01', '2025-11-07', 'Brazil', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(9, 'Machu Picchu Trek', 'A hiking tour to Machu Picchu.', 2900.00, '2025-06-20', '2025-06-27', 'Peru', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(10, 'Paris City Tour', 'Explore the beauty of Paris.', 1200.00, '2025-07-15', '2025-07-20', 'France', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(11, 'Egyptian Pyramids', 'Discover the wonders of Egypt.', 2100.00, '2025-08-10', '2025-08-15', 'Egypt', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(12, 'Tokyo Experience', 'A modern and traditional Japan experience.', 2500.00, '2025-09-05', '2025-09-12', 'Japan', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(13, 'Swiss Alps Adventure', 'Ski and hike in Switzerland.', 3000.00, '2025-10-15', '2025-10-22', 'Switzerland', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(14, 'Bali Retreat', 'A relaxing tour in Bali.', 1700.00, '2025-11-01', '2025-11-07', 'Indonesia', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(15, 'New York City Tour', 'Explore the city that never sleeps.', 1300.00, '2025-06-10', '2025-06-15', 'USA', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(16, 'Venice Romance', 'A romantic gondola ride experience.', 2200.00, '2025-07-20', '2025-07-25', 'Italy', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(17, 'Sydney Adventure', 'Enjoy Australia’s best attractions.', 2700.00, '2025-08-10', '2025-08-17', 'Australia', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(18, 'Rio Carnival Tour', 'Join the famous Rio Carnival.', 2000.00, '2025-09-20', '2025-09-27', 'Brazil', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(19, 'Dubai Luxury Tour', 'Experience the luxury of Dubai.', 3500.00, '2025-10-05', '2025-10-12', 'UAE', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(20, 'Grand Canyon Hike', 'Hike through the Grand Canyon.', 1800.00, '2025-11-15', '2025-11-22', 'USA', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(21, 'South African Safari', 'Explore the wildlife in South Africa.', 3200.00, '2025-12-01', '2025-12-10', 'South Africa', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(22, 'Thailand Island Tour', 'Visit Thailand’s beautiful islands.', 2000.00, '2025-12-15', '2025-12-22', 'Thailand', NULL, NULL, '2025-03-26 09:02:10', 'active', 0, 0),
(23, 'SHEKA', 'sheka zone', 555.00, '2025-03-27', '2025-03-31', 'Chelsea', NULL, '', '2025-03-26 22:06:45', 'active', 0, 0),
(24, 'SHEKA', 'uuuuuuuu', 555.00, '2025-04-03', '2025-03-26', 'Chelsea', NULL, '', '2025-03-26 22:14:54', 'active', 0, 0),
(25, 'SHEKA', 'yyy', 77777.00, '2025-04-01', '2025-04-05', 'Chelsea', NULL, '', '2025-03-26 22:38:32', 'active', 0, 0),
(26, 'uuuuu', 'uuuu', 56.00, '2025-05-06', '2025-03-29', 'uuuu', NULL, '', '2025-03-31 03:29:23', 'active', 0, 0),
(27, 'Simien Mountains Trekking', 'Experience the breathtaking views of the Simien Mountains.', 1200.00, '2025-08-01', '2025-08-07', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 1),
(28, 'Danakil Depression Adventure', 'Explore one of the hottest places on Earth.', 1500.00, '2025-09-15', '2025-09-22', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 0),
(29, 'Lalibela Rock-hewn Churches Tour', 'Visit the UNESCO World Heritage sites of Lalibela.', 900.00, '2025-07-01', '2025-07-04', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 1),
(30, 'Omo Valley Cultural Tour', 'Experience the diverse cultures of the Omo Valley.', 2000.00, '2025-06-15', '2025-06-25', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 1),
(31, 'Awash National Park Safari', 'Discover wildlife in the beautiful Awash National Park.', 1100.00, '2025-10-10', '2025-10-15', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 0),
(32, 'Harar City Historical Tour', 'Visit the historic city of Harar, known for its ancient walls.', 800.00, '2025-11-05', '2025-11-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 1),
(33, 'Blue Nile Falls Excursion', 'Marvel at the stunning Blue Nile Falls.', 300.00, '2025-11-20', '2025-11-22', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 1),
(34, 'Bale Mountains National Park Trek', 'Experience the unique fauna of the Bale Mountains.', 1300.00, '2025-12-10', '2025-12-15', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 0),
(35, 'Addis Ababa City Tour', 'Explore the capital city of Ethiopia.', 500.00, '2025-05-15', '2025-05-17', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 1),
(36, 'Gonder Historical Tour', 'Visit the castles of Gonder, a UNESCO World Heritage site.', 700.00, '2025-10-20', '2025-10-25', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 0),
(37, 'Aksum Historical Expedition', 'Discover the ancient city of Aksum.', 950.00, '2025-09-05', '2025-09-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 1),
(38, 'Lake Tana Boat Trip', 'Cruise on Lake Tana and visit the island monasteries.', 400.00, '2025-08-20', '2025-08-25', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 0),
(39, 'Kafa Biosphere Reserve Trek', 'Experience the biodiversity of Kafa.', 1100.00, '2025-07-15', '2025-07-20', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 0),
(40, 'Danakil Depression Expedition', 'Experience the unique geology and culture of the Danakil.', 1750.00, '2025-06-05', '2025-06-12', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 1, 1),
(41, 'Southern Ethiopia Cultural Tour', 'Experience various tribes and their cultures.', 1900.00, '2025-11-01', '2025-11-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 0),
(42, 'Gurage Region Experiences', 'Explore the Gurage culture and culinary experiences.', 1200.00, '2025-12-01', '2025-12-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:27:34', 'active', 0, 0),
(43, 'Simien Mountains Trekking', 'Experience the breathtaking views of the Simien Mountains.', 1200.00, '2025-08-01', '2025-08-07', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 1),
(44, 'Danakil Depression Adventure', 'Explore one of the hottest places on Earth.', 1500.00, '2025-09-15', '2025-09-22', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 0),
(45, 'Lalibela Rock-hewn Churches Tour', 'Visit the UNESCO World Heritage sites of Lalibela.', 900.00, '2025-07-01', '2025-07-04', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 1),
(46, 'Omo Valley Cultural Tour', 'Experience the diverse cultures of the Omo Valley.', 2000.00, '2025-06-15', '2025-06-25', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 1),
(47, 'Awash National Park Safari', 'Discover wildlife in the beautiful Awash National Park.', 1100.00, '2025-10-10', '2025-10-15', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 0),
(48, 'Harar City Historical Tour', 'Visit the historic city of Harar, known for its ancient walls.', 800.00, '2025-11-05', '2025-11-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 1),
(49, 'Blue Nile Falls Excursion', 'Marvel at the stunning Blue Nile Falls.', 300.00, '2025-11-20', '2025-11-22', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 1),
(50, 'Bale Mountains National Park Trek', 'Experience the unique fauna of the Bale Mountains.', 1300.00, '2025-12-10', '2025-12-15', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 0),
(51, 'Addis Ababa City Tour', 'Explore the capital city of Ethiopia.', 500.00, '2025-05-15', '2025-05-17', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 1),
(52, 'Gonder Historical Tour', 'Visit the castles of Gonder, a UNESCO World Heritage site.', 700.00, '2025-10-20', '2025-10-25', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 0),
(53, 'Aksum Historical Expedition', 'Discover the ancient city of Aksum.', 950.00, '2025-09-05', '2025-09-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 1),
(54, 'Lake Tana Boat Trip', 'Cruise on Lake Tana and visit the island monasteries.', 400.00, '2025-08-20', '2025-08-25', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 0),
(55, 'Kafa Biosphere Reserve Trek', 'Experience the biodiversity of Kafa.', 1100.00, '2025-07-15', '2025-07-20', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 0),
(56, 'Danakil Depression Expedition', 'Experience the unique geology and culture of the Danakil.', 1750.00, '2025-06-05', '2025-06-12', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 1, 1),
(57, 'Southern Ethiopia Cultural Tour', 'Experience various tribes and their cultures.', 1900.00, '2025-11-01', '2025-11-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 0),
(58, 'Gurage Region Experiences', 'Explore the Gurage culture and culinary experiences.', 1200.00, '2025-12-01', '2025-12-10', 'Ethiopia', NULL, NULL, '2025-04-02 00:39:44', 'active', 0, 0),
(67, 'yihunn', 'fffffff', 3444.00, '2025-04-10', '2025-04-22', 'england', 'uploads/yihunn/main image/1743796020_images.jpg', '', '2025-04-04 19:47:00', 'active', 0, 0),
(68, 'stamford', 'fffffffff', 3444.00, '2025-04-23', '2025-05-01', 'england', 'uploads/stamford/main image/1743796102_images.jpg', '', '2025-04-04 19:48:22', 'active', 1, 1),
(69, 'Grand Canyon Adventure', 'Discover the majestic views of the Grand Canyon.', 199.99, '2023-10-01', '2023-10-10', 'Arizona, USA', NULL, NULL, '2025-04-10 14:34:12', 'active', 0, 0),
(70, 'Safari in Kenya', 'Experience the wildlife up close in a memorable safari.', 1299.99, '2023-11-01', '2023-11-15', 'Nairobi, Kenya', NULL, NULL, '2025-04-10 14:34:12', 'active', 0, 0),
(71, 'Romantic Getaway to Paris', 'A romantic trip to explore the city of love.', 799.99, '2023-12-01', '2023-12-10', 'Paris, France', NULL, NULL, '2025-04-10 14:34:12', 'active', 0, 0),
(72, 'Alaskan Cruise', 'Enjoy breathtaking scenery on an Alaskan adventure.', 1599.99, '2024-05-15', '2024-05-30', 'Alaska, USA', NULL, NULL, '2025-04-10 14:34:12', 'active', 0, 0),
(73, 'Beach Holiday in Hawaii', 'Relax on the beautiful beaches of Hawaii.', 1199.99, '2024-06-01', '2024-06-15', 'Hawaii, USA', NULL, NULL, '2025-04-10 14:34:12', 'active', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tour_ratings`
--

CREATE TABLE `tour_ratings` (
  `rating_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tour_ratings`
--

INSERT INTO `tour_ratings` (`rating_id`, `tour_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 1, 1, 5.00, '2025-04-02 00:27:34'),
(2, 1, 2, 4.00, '2025-04-02 00:27:34'),
(3, 1, 3, 3.00, '2025-04-02 00:27:34'),
(4, 1, 4, 5.00, '2025-04-02 00:27:34'),
(5, 1, 5, 4.00, '2025-04-02 00:27:34'),
(6, 2, 2, 2.00, '2025-04-02 00:27:34'),
(7, 2, 3, 5.00, '2025-04-02 00:27:34'),
(8, 2, 4, 1.00, '2025-04-02 00:27:34'),
(9, 2, 5, 4.00, '2025-04-02 00:27:34'),
(10, 2, 6, 3.00, '2025-04-02 00:27:34'),
(11, 3, 1, 4.00, '2025-04-02 00:27:34'),
(12, 3, 2, 5.00, '2025-04-02 00:27:34'),
(13, 3, 3, 3.00, '2025-04-02 00:27:34'),
(14, 3, 4, 2.00, '2025-04-02 00:27:34'),
(15, 3, 5, 1.00, '2025-04-02 00:27:34'),
(16, 4, 3, 5.00, '2025-04-02 00:27:34'),
(17, 4, 4, 4.00, '2025-04-02 00:27:34'),
(18, 4, 5, 3.00, '2025-04-02 00:27:34'),
(19, 4, 6, 2.00, '2025-04-02 00:27:34'),
(20, 4, 7, 1.00, '2025-04-02 00:27:34'),
(21, 5, 2, 5.00, '2025-04-02 00:27:34'),
(22, 5, 3, 4.00, '2025-04-02 00:27:34'),
(23, 5, 4, 2.00, '2025-04-02 00:27:34'),
(24, 5, 5, 1.00, '2025-04-02 00:27:34'),
(25, 5, 6, 3.00, '2025-04-02 00:27:34'),
(26, 6, 1, 3.00, '2025-04-02 00:27:34'),
(27, 6, 2, 4.00, '2025-04-02 00:27:34'),
(28, 6, 3, 5.00, '2025-04-02 00:27:34'),
(29, 7, 4, 4.00, '2025-04-02 00:27:34'),
(30, 7, 5, 5.00, '2025-04-02 00:27:34'),
(31, 8, 1, 2.00, '2025-04-02 00:27:34'),
(32, 8, 2, 4.00, '2025-04-02 00:27:34'),
(33, 8, 3, 1.00, '2025-04-02 00:27:34'),
(34, 8, 4, 5.00, '2025-04-02 00:27:34'),
(35, 9, 2, 5.00, '2025-04-02 00:27:34'),
(36, 9, 3, 4.00, '2025-04-02 00:27:34'),
(37, 10, 1, 3.00, '2025-04-02 00:27:34'),
(38, 10, 4, 2.00, '2025-04-02 00:27:34'),
(39, 10, 5, 1.00, '2025-04-02 00:27:34'),
(40, 1, 94, 5.00, '2025-04-02 00:40:45'),
(41, 1, 95, 4.00, '2025-04-02 00:40:45'),
(42, 1, 96, 3.00, '2025-04-02 00:40:45'),
(43, 1, 97, 5.00, '2025-04-02 00:40:45'),
(44, 1, 98, 2.00, '2025-04-02 00:40:45'),
(45, 2, 94, 4.00, '2025-04-02 00:40:45'),
(46, 2, 95, 5.00, '2025-04-02 00:40:45'),
(47, 2, 96, 3.00, '2025-04-02 00:40:45'),
(48, 2, 97, 2.00, '2025-04-02 00:40:45'),
(49, 2, 98, 1.00, '2025-04-02 00:40:45'),
(50, 3, 99, 4.00, '2025-04-02 00:40:45'),
(51, 3, 100, 5.00, '2025-04-02 00:40:45'),
(52, 3, 101, 3.00, '2025-04-02 00:40:45'),
(53, 3, 102, 2.00, '2025-04-02 00:40:45'),
(54, 3, 103, 1.00, '2025-04-02 00:40:45'),
(55, 4, 98, 5.00, '2025-04-02 00:40:45'),
(56, 4, 99, 4.00, '2025-04-02 00:40:45'),
(57, 4, 100, 3.00, '2025-04-02 00:40:45'),
(58, 4, 101, 4.00, '2025-04-02 00:40:45'),
(59, 4, 102, 5.00, '2025-04-02 00:40:45'),
(60, 5, 103, 1.00, '2025-04-02 00:40:45'),
(61, 5, 104, 2.00, '2025-04-02 00:40:45'),
(62, 5, 105, 4.00, '2025-04-02 00:40:45'),
(63, 5, 106, 3.00, '2025-04-02 00:40:45'),
(64, 5, 107, 5.00, '2025-04-02 00:40:45'),
(65, 6, 108, 3.00, '2025-04-02 00:40:45'),
(66, 6, 109, 4.00, '2025-04-02 00:40:45'),
(67, 6, 110, 5.00, '2025-04-02 00:40:45'),
(68, 6, 111, 2.00, '2025-04-02 00:40:45'),
(69, 7, 94, 3.00, '2025-04-02 00:40:45'),
(70, 7, 95, 5.00, '2025-04-02 00:40:45'),
(71, 7, 96, 4.00, '2025-04-02 00:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Traveler','Admin') DEFAULT 'Traveler',
  `profile_picture` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `preferred_language` varchar(50) DEFAULT NULL,
  `home_address` text DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `role`, `profile_picture`, `nationality`, `phone_number`, `date_of_birth`, `preferred_language`, `home_address`, `gender`, `created_at`) VALUES
(1, 'YIHUN BEFKADU', 'admin@gmail.com', '$2y$10$hMkyJISxF9ACoVQMgtWhfOCY7ZKD1iADWFISZEHUEyc6y2VWfkMXq', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 03:05:39'),
(2, 'YIHUN BEFKADU', 'admin@gmail.comt', '$2y$10$X14rbb7.aGob5L56EgC66.FM6leEMv0Pf3kH3EjEXediLHRyJsIwu', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 03:27:44'),
(3, 'Eden Hazard', 'eden@gmail.com', '$2y$10$OmybgRw2mIZELIS8EqdVCeVZ3gkcwcahH9rdpoFXki/Yqf5O3br1u', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 04:37:00'),
(4, 'John Doe', 'john@example.com', 'hashedpassword123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 08:58:07'),
(5, 'Alice Johnson', 'alice@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(6, 'Bob Smith', 'bob@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(7, 'Charlie Davis', 'charlie@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(8, 'Diana Miller', 'diana@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(9, 'Ethan Brown', 'ethan@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(10, 'Fiona Wilson', 'fiona@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(11, 'George Clark', 'george@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(12, 'Hannah Lewis', 'hannah@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(13, 'Ian Walker', 'ian@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(14, 'Julia Hall', 'julia@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(15, 'Kevin Allen', 'kevin@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(16, 'Laura Young', 'laura@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(17, 'Michael Scott', 'michael@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(19, 'Oscar King', 'oscar@example.com', 'password123', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 09:01:45'),
(20, 'John Doe', 'johndoe@example.com', 'hashed_password_1', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(21, 'Jane Smith', 'janesmith@example.com', 'hashed_password_2', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(22, 'Alice Johnson', 'alicejohnson@example.com', 'hashed_password_3', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(23, 'Bob Brown', 'bobbrown@example.com', 'hashed_password_4', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(24, 'Charlie Davis', 'charliedavis@example.com', 'hashed_password_5', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(25, 'David Wilson', 'davidwilson@example.com', 'hashed_password_6', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(26, 'Emma Garcia', 'emmagarcia@example.com', 'hashed_password_7', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(27, 'Fiona Martinez', 'fionamartinez@example.com', 'hashed_password_8', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(28, 'George Taylor', 'georgetaylor@example.com', 'hashed_password_9', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(29, 'Hannah Thomas', 'hannahthomas@example.com', 'hashed_password_10', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(30, 'Ian Anderson', 'iananderson@example.com', 'hashed_password_11', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(31, 'Jane Adams', 'janeadams@example.com', 'hashed_password_12', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(32, 'Kevin Scott', 'kevinscott@example.com', 'hashed_password_13', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(33, 'Laura Lee', 'lauralee@example.com', 'hashed_password_14', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(34, 'Mike Harris', 'mikeharris@example.com', 'hashed_password_15', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(35, 'Nina Clark', 'ninaclark@example.com', 'hashed_password_16', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(36, 'Oscar Hall', 'oscarhall@example.com', 'hashed_password_17', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(37, 'Pamela Lewis', 'pamelalewis@example.com', 'hashed_password_18', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(38, 'Quinn Walker', 'quinnwalker@example.com', 'hashed_password_19', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(39, 'Rick Young', 'rickyoung@example.com', 'hashed_password_20', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(40, 'Sophia King', 'sophiaking@example.com', 'hashed_password_21', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(41, 'Tom Green', 'tomgreen@example.com', 'hashed_password_22', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(42, 'Uma Edwards', 'umaedwards@example.com', 'hashed_password_23', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(43, 'Victor Ramirez', 'victorramirez@example.com', 'hashed_password_24', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(44, 'Wendy Bennett', 'wendybennett@example.com', 'hashed_password_25', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(45, 'Xavier Hughes', 'xavierhughes@example.com', 'hashed_password_26', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-27 13:59:18'),
(46, 'YIHUN BEFKADU', 'weniichrist@gmail.com', '$2y$10$WxWn7bAZXB9GkdujO9zCTe0i46AyMIEaHxS40V1pPkrmidRVGrRbG', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31 03:01:02'),
(47, 'ttttttttt', 'Wongel@Gmail.com', '$2y$10$IhDPA70/NQbXnswrFU6pqeOvR71QZ7R4rwRGucf7ENLBX.OjaJQSm', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31 10:06:36'),
(48, 'Abebe Kedir', 'abebe.kedir@example.com', 'hashed_password_1', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(49, 'Fatuma Ali', 'fatuma.ali@example.com', 'hashed_password_2', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(50, 'Mekonnen Assefa', 'mekonnen.assefa@example.com', 'hashed_password_3', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(51, 'Selamawit Tesfaye', 'selamawit.tesfaye@example.com', 'hashed_password_4', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(52, 'Yared Desta', 'yared.desta@example.com', 'hashed_password_5', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(53, 'Hana Asefa', 'hana.asefa@example.com', 'hashed_password_6', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(55, 'Marta Ghebremedhin', 'marta.ghebremedhin@example.com', 'hashed_password_8', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(56, 'Samuel Guar', 'samuel.guar@example.com', 'hashed_password_9', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(57, 'Yemisirach Amha', 'yemisirach.amha@example.com', 'hashed_password_10', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:30:28'),
(94, 'Abebe Kedir', 'abebe.kedir@gaffar.com', '$2y$10$D9uXnK8y6.dV0RW0cEo.O.O6WgZbbkCH5X3H.K2Jae7Pqi0MLGtZW', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(95, 'Fatuma Ali', 'fatuma.ali@gaffar.com', '$2y$10$9aR671AiA33tXUeYo5..4Oj/EKBV3vnSnxhK2/z1.5uZYomzoS5AC', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(96, 'Mekonnen Assefa', 'mekonnen.assefa@gaffar.com', '$2y$10$QeSOIhi8xZPAzpdcsYa9veUS0s8ICiZxRf2nTbsVjgc6/UZ4XVnZK', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(97, 'Selamawit Tesfaye', 'selamawit.tesfaye@gaffar.com', '$2y$10$hMkyJISxF9ACoVQMgtWhfOCY7ZKD1iADWFISZEHUEyc6y2VWfkMXq', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(98, 'Yared Desta', 'yared.desta@gaffar.com', '$2y$10$d1KW7sfv09sFPKTI4.SwbOo2Q9D0g/d3fhcXF4seBzZ8KhLRch5We', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(99, 'Hana Asefa', 'hana.asefa@gaffar.com', '$2y$10$XbT6KbAMogdy1ZiB7F0MeA0lGGO7B9/Fm4ySz0g9UaghDyzY.zA8K', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(100, 'Kebede Gashaw', 'kebede.gashaw@gaffar.com', '$2y$10$MWfg7pBLmzqihdXT/bWweu2Pr7Bcm83hILBMU41FM7LHl72pDydA5', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(101, 'Marta Ghebremedhin', 'marta.ghebremedhin@gaffar.com', '$2y$10$8PCgokSiWBuMo5j6p4jz0uLVCgPpx1aRFPRP2N2auEBv8p.aJHiAC', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(102, 'Samuel Guar', 'samuel.guar@gaffar.com', '$2y$10$lpq4SQ7ZjMZB0CQOTZLhAuD/Xt1Z3/2R1P4E4Zj/9EFm8o91a7n.f', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(103, 'Yemisirach Amha', 'yemisirach.amha@gaffar.com', '$2y$10$6fi8eAockA5WhtlJEuF2uu6XrGo3n9aJYHm.Ooo5WZOlB1Dyjx.TK', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(104, 'Sani Ali', 'sani.ali@gaffar.com', '$2y$10$5sD.CBGpDb2l.rcGMob0Aul1YwP2yrM5dHh0HXu/liy0hR9HWHJ4u', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(105, 'Asha Mohammed', 'asha.mohammed@gaffar.com', '$2y$10$9nqs8oze5dQi7NGyJTbdhOQkPEZ.4HtVBGp35nHsxUUls2vmMtsNO', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(106, 'Dawit Bekele', 'dawit.bekele@gaffar.com', '$2y$10$7tipON0PyUGPBZ5dqFm1ZOSFi8THI4CRaIOxDtaBUKPf3Ugx9dyX.', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(107, 'Lily Fesseha', 'lily.fesseha@gaffar.com', '$2y$10$9nqs8oze5dQi7NGyJTbdhOQkPEZ.4HtVBGp35nHsxUUls2vmMtsNO', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(108, 'Aklilu Demissie', 'aklilu.demissie@gaffar.com', '$2y$10$D6thNK8UaY.sSOQCuY2IDIzhyAre5l.CpS4T3VXtF3jpZSt2RyRby', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(109, 'Selam Abraha', 'selam.abraha@gaffar.com', '$2y$10$x9f5W4PkpVisuQ7g2QkmpeX83Z58WPm9dvR12H0DJpEUaCy92E24m', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(110, 'Girum Tadesse', 'girum.tadesse@gaffar.com', '$2y$10$3xAy1H8GJ5ZQWrynIi3QwOQJ3FsNfjtxq:', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(111, 'Biniam Hailu', 'biniam.hailu@gaffar.com', '$2y$10$yHR5hxUicci0pa0GxOHZ3u9FOZcobUxs2HZ3uZrS2FJ6.VTf0hDne', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 00:33:29'),
(113, 'Alice Johnson', 'alice_club@email.com', 'hashed_password_1', 'Traveler', NULL, 'American', '555-0101', '1990-05-15', 'English', '123 Elm St, Cityville', 'Female', '2025-04-10 14:24:35'),
(114, 'Bob Smith', 'bob_club@email.com', 'hashed_password_2', 'Traveler', NULL, 'Canadian', '555-0102', '1988-08-22', 'English', '456 Oak St, Townsville', 'Male', '2025-04-10 14:24:35'),
(115, 'Charlie Brown', 'charlie_club@email.com', 'hashed_password_3', 'Admin', NULL, 'British', '555-0103', '1985-12-30', 'English', '789 Pine St, Villagetown', 'Male', '2025-04-10 14:24:35'),
(116, 'Alice Johnson', 'alice_johnson@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(117, 'Bob Smith', 'bob_smith@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(118, 'Charlie Brown', 'charlie_brown@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(119, 'David Lee', 'david_lee@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(120, 'Emma White', 'emma_white@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(121, 'Fiona Green', 'fiona_green@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(122, 'George Black', 'george_black@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(123, 'Hannah Blue', 'hannah_blue@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(124, 'Ian Gray', 'ian_gray@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51'),
(125, 'Jessica Red', 'jessica_red@email.com', '', 'Traveler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `videogallery`
--

CREATE TABLE `videogallery` (
  `gallery_video_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `videogallery`
--

INSERT INTO `videogallery` (`gallery_video_id`, `tour_id`, `video_url`, `description`, `uploaded_at`) VALUES
(1, 24, 'https://www.youtube.com/watch?v=jdiy6sz1-fk', NULL, '2025-03-26 22:14:55'),
(2, 24, 'https://www.youtube.com/watch?v=UAB7tMRXJU4', NULL, '2025-03-26 22:14:55'),
(3, 25, 'https://www.youtube.com/watch?v=jdiy6sz1-fk', NULL, '2025-03-26 22:38:32'),
(4, 25, 'https://www.youtube.com/watch?v=jdiy6sz1-fk', NULL, '2025-03-26 22:38:32'),
(5, 25, 'https://www.youtube.com/watch?v=jdiy6sz1-fk', NULL, '2025-03-26 22:38:32'),
(6, 26, 'https://www.youtube.com/shorts/fO2wodITbD4', NULL, '2025-03-31 03:29:23'),
(7, 26, 'https://www.youtube.com/shorts/fO2wodITbD4', NULL, '2025-03-31 03:29:23'),
(8, 59, 'uploads/ERMICH/videos/1743772485_vidu-video-2673996992308054.mp4', NULL, '2025-04-04 13:14:45'),
(9, 59, 'uploads/ERMICH/videos/1743772485_vidu-video-2673998336669239.mp4', NULL, '2025-04-04 13:14:45'),
(10, 59, 'uploads/ERMICH/videos/1743772485_vidu-video-2674029147675035.mp4', NULL, '2025-04-04 13:14:45'),
(11, 59, 'uploads/ERMICH/videos/1743772485_vidu-video-2674029672769003.mp4', NULL, '2025-04-04 13:14:45'),
(12, 59, 'uploads/ERMICH/videos/1743772485_vidu-video-2674030503004300 (1).mp4', NULL, '2025-04-04 13:14:45'),
(13, 59, 'uploads/ERMICH/videos/1743772485_vidu-video-2674030503004300.mp4', NULL, '2025-04-04 13:14:45'),
(14, 59, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:14:45'),
(15, 59, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:14:45'),
(16, 59, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:14:45'),
(17, 60, 'uploads/ERMICH/videos/1743772773_vidu-video-2673996992308054.mp4', NULL, '2025-04-04 13:19:33'),
(18, 60, 'uploads/ERMICH/videos/1743772773_vidu-video-2673998336669239.mp4', NULL, '2025-04-04 13:19:33'),
(19, 60, 'uploads/ERMICH/videos/1743772773_vidu-video-2674029147675035.mp4', NULL, '2025-04-04 13:19:33'),
(20, 60, 'uploads/ERMICH/videos/1743772773_vidu-video-2674029672769003.mp4', NULL, '2025-04-04 13:19:33'),
(21, 60, 'uploads/ERMICH/videos/1743772773_vidu-video-2674030503004300 (1).mp4', NULL, '2025-04-04 13:19:33'),
(22, 60, 'uploads/ERMICH/videos/1743772773_vidu-video-2674030503004300.mp4', NULL, '2025-04-04 13:19:33'),
(23, 60, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:19:33'),
(24, 60, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:19:33'),
(25, 60, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:19:33'),
(26, 61, 'uploads/SHEKA/videos/1743772851_vidu-video-2674642818865732.mp4', NULL, '2025-04-04 13:20:51'),
(27, 61, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:20:51'),
(28, 62, 'uploads/SHEKAtt/videos/1743773227_vidu-video-2674047756550514.mp4', NULL, '2025-04-04 13:27:07'),
(29, 62, 'uploads/SHEKAtt/videos/1743773227_vidu-video-2674050913192141 (1).mp4', NULL, '2025-04-04 13:27:07'),
(30, 62, 'uploads/SHEKAtt/videos/1743773227_vidu-video-2674050913192141.mp4', NULL, '2025-04-04 13:27:07'),
(31, 62, 'uploads/SHEKAtt/videos/1743773227_vidu-video-2674053283166432.mp4', NULL, '2025-04-04 13:27:07'),
(32, 62, 'uploads/SHEKAtt/videos/1743773227_vidu-video-2674054949053896.mp4', NULL, '2025-04-04 13:27:07'),
(33, 62, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:27:07'),
(34, 63, 'uploads/SHEKA/videos/1743773276_vidu-video-2673996992308054.mp4', NULL, '2025-04-04 13:27:56'),
(35, 63, 'uploads/SHEKA/videos/1743773276_vidu-video-2673998336669239.mp4', NULL, '2025-04-04 13:27:56'),
(36, 63, 'uploads/SHEKA/videos/1743773276_vidu-video-2674029147675035.mp4', NULL, '2025-04-04 13:27:56'),
(37, 63, 'uploads/SHEKA/videos/1743773276_vidu-video-2674029672769003.mp4', NULL, '2025-04-04 13:27:56'),
(38, 63, 'uploads/SHEKA/videos/1743773276_vidu-video-2674030503004300 (1).mp4', NULL, '2025-04-04 13:27:56'),
(39, 63, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:27:56'),
(40, 64, 'uploads/MESSI/videos/1743773934_vidu-video-2674050913192141 (1).mp4', NULL, '2025-04-04 13:38:54'),
(41, 64, 'uploads/MESSI/videos/1743773934_vidu-video-2674050913192141.mp4', NULL, '2025-04-04 13:38:54'),
(42, 64, 'uploads/MESSI/videos/1743773934_vidu-video-2674053283166432.mp4', NULL, '2025-04-04 13:38:54'),
(43, 64, 'uploads/MESSI/videos/1743773934_vidu-video-2674054949053896.mp4', NULL, '2025-04-04 13:38:54'),
(44, 64, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 13:38:54'),
(45, 65, 'uploads/brentford/videos/1743790818_vidu-video-2674053283166432.mp4', NULL, '2025-04-04 18:20:18'),
(46, 65, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 18:20:18'),
(47, 66, 'uploads/brentford/videos/1743791338_vidu-video-2674047756550514.mp4', NULL, '2025-04-04 18:28:58'),
(48, 66, 'uploads/brentford/videos/1743791338_vidu-video-2674050913192141 (1).mp4', NULL, '2025-04-04 18:28:58'),
(49, 66, 'uploads/brentford/videos/1743791338_vidu-video-2674050913192141.mp4', NULL, '2025-04-04 18:28:58'),
(50, 66, 'uploads/brentford/videos/1743791338_vidu-video-2674053283166432.mp4', NULL, '2025-04-04 18:28:58'),
(51, 66, 'uploads/brentford/videos/1743791338_vidu-video-2674054949053896.mp4', NULL, '2025-04-04 18:28:58'),
(52, 66, 'uploads/brentford/videos/1743791338_vidu-video-2674055964798576.mp4', NULL, '2025-04-04 18:28:58'),
(53, 66, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 18:28:58'),
(54, 67, 'uploads/yihunn/videos/1743796020_vidu-video-2673996992308054.mp4', NULL, '2025-04-04 19:47:00'),
(55, 67, 'uploads/yihunn/videos/1743796020_vidu-video-2673998336669239.mp4', NULL, '2025-04-04 19:47:00'),
(56, 67, 'uploads/yihunn/videos/1743796020_vidu-video-2674029147675035.mp4', NULL, '2025-04-04 19:47:00'),
(57, 67, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 19:47:00'),
(58, 68, 'uploads/stamford/videos/1743796102_vidu-video-2673996992308054.mp4', NULL, '2025-04-04 19:48:22'),
(59, 68, 'uploads/stamford/videos/1743796102_vidu-video-2673998336669239.mp4', NULL, '2025-04-04 19:48:22'),
(60, 68, 'uploads/stamford/videos/1743796102_vidu-video-2674029147675035.mp4', NULL, '2025-04-04 19:48:22'),
(61, 68, 'uploads/stamford/videos/1743796102_vidu-video-2674029672769003.mp4', NULL, '2025-04-04 19:48:22'),
(62, 68, 'uploads/stamford/videos/1743796102_vidu-video-2674030503004300 (1).mp4', NULL, '2025-04-04 19:48:22'),
(63, 68, 'https://www.youtube.com/watch?v=hdbt1d_zBA8', NULL, '2025-04-04 19:48:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `galleryimages`
--
ALTER TABLE `galleryimages`
  ADD PRIMARY KEY (`gallery_image_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `multimedia`
--
ALTER TABLE `multimedia`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`subscriber_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`tour_id`);

--
-- Indexes for table `tour_ratings`
--
ALTER TABLE `tour_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videogallery`
--
ALTER TABLE `videogallery`
  ADD PRIMARY KEY (`gallery_video_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `galleryimages`
--
ALTER TABLE `galleryimages`
  MODIFY `gallery_image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `multimedia`
--
ALTER TABLE `multimedia`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `subscriber_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `tour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tour_ratings`
--
ALTER TABLE `tour_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `videogallery`
--
ALTER TABLE `videogallery`
  MODIFY `gallery_video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`tour_id`, `title`, `description`, `price`, `start_date`, `end_date`, `location`, `main_image`, `video_url`, `created_at`, `status`, `featured`, `recommended`) VALUES
(59, 'ERMICH', 'ERMICH', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/ERMICH/main image/1743772485_piclumen-1741042865150.png', '', '2025-04-04 13:14:45', 'active', 0, 0),
(60, 'ERMICH', 'ERMICH', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/ERMICH/main image/1743772773_piclumen-1741042865150.png', '', '2025-04-04 13:19:33', 'active', 0, 0),
(61, 'SHEKA', 'SHEKA', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/SHEKA/main image/1743772851_piclumen-1741043200870.png', '', '2025-04-04 13:20:51', 'active', 0, 0),
(62, 'SHEKAtt', 'SHEKAtt', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/SHEKAtt/main image/1743773227_piclumen-1741043200870.png', '', '2025-04-04 13:27:07', 'active', 0, 0),
(63, 'SHEKA', 'SHEKA', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/SHEKA/main image/1743773276_piclumen-1741043200870.png', '', '2025-04-04 13:27:56', 'active', 0, 0),
(64, 'MESSI', 'MESSI', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/MESSI/main image/1743773934_piclumen-1741043257343.png', '', '2025-04-04 13:38:54', 'active', 0, 0),
(65, 'brentford', 'brentford', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/brentford/main image/1743790818_piclumen-1741043200870.png', '', '2025-04-04 18:20:18', 'active', 0, 0),
(66, 'brentford', 'brentford', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/brentford/main image/1743791338_piclumen-1741043200870.png', '', '2025-04-04 18:28:58', 'active', 0, 0),
(67, 'yihunn', 'yihunn', 555.00, '2025-04-04', '2025-04-05', 'Chelsea', 'uploads/yihunn/main image/1743796020_alexander-slattery-LI748t0BK8w-unsplash.jpg', '', '2025-04-04 19:47:00', 'active', 0, 0),
(68, 'stamford', 'fffffffff', 3444.00, '2025-04-23', '2025-05-01', 'england', 'uploads/stamford/main image/1743796102_images.jpg', '', '2025-04-04 19:48:22', 'active', 1, 1);

--
-- Constraints for table `galleryimages`
--
ALTER TABLE `galleryimages`
  ADD CONSTRAINT `galleryimages_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);

--
-- Constraints for table `multimedia`
--
ALTER TABLE `multimedia`
  ADD CONSTRAINT `multimedia_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);

--
-- Constraints for table `tour_ratings`
--
ALTER TABLE `tour_ratings`
  ADD CONSTRAINT `tour_ratings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);

--
-- Constraints for table `videogallery`
--
ALTER TABLE `videogallery`
  ADD CONSTRAINT `videogallery_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
