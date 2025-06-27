-- Database creation
CREATE DATABASE IF NOT EXISTS `asgardtour` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `asgardtour`;

-- Table structure for table `users`
CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('Traveler','Admin') DEFAULT 'Traveler',
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  `nationality` VARCHAR(100) DEFAULT NULL,
  `phone_number` VARCHAR(15) DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `preferred_language` VARCHAR(50) DEFAULT NULL,
  `home_address` TEXT DEFAULT NULL,
  `gender` ENUM('Male','Female') DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tours`
CREATE TABLE `tours` (
  `tour_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `main_image` VARCHAR(255) DEFAULT NULL,
  `video_url` VARCHAR(255) DEFAULT NULL,
  `rating` DECIMAL(3,2) DEFAULT 0.00,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `bookings`
CREATE TABLE `bookings` (
  `booking_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `tour_id` INT(11) NOT NULL,
  `booking_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('Confirmed','Pending','Cancelled') DEFAULT 'Pending',
  `booking_day` DATE DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `reviews`
CREATE TABLE `reviews` (
  `review_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `tour_id` INT(11) NOT NULL,
  `rating` INT(11) DEFAULT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `blogs`
CREATE TABLE `blogs` (
  `blog_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `likes` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `galleryimages`
CREATE TABLE `galleryimages` (
  `gallery_image_id` INT(11) NOT NULL AUTO_INCREMENT,
  `tour_id` INT(11) NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gallery_image_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `videogallery`
CREATE TABLE `videogallery` (
  `gallery_video_id` INT(11) NOT NULL AUTO_INCREMENT,
  `tour_id` INT(11) NOT NULL,
  `video_url` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gallery_video_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `notifications`
CREATE TABLE `notifications` (
  `notification_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('Booking','Review','Milestone','Alert') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `multimedia`
CREATE TABLE `multimedia` (
  `media_id` INT(11) NOT NULL AUTO_INCREMENT,
  `tour_id` INT(11) NOT NULL,
  `media_type` ENUM('Image','Video') NOT NULL,
  `media_url` TEXT NOT NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`media_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Adding indexes for better performance
ALTER TABLE `bookings`
  ADD INDEX `idx_user_id` (`user_id`),
  ADD INDEX `idx_tour_id` (`tour_id`);

ALTER TABLE `reviews`
  ADD INDEX `idx_review_user` (`user_id`),
  ADD INDEX `idx_review_tour` (`tour_id`);

ALTER TABLE `blogs`
  ADD INDEX `idx_blog_user` (`user_id`);

ALTER TABLE `galleryimages`
  ADD INDEX `idx_gallery_tour` (`tour_id`);

ALTER TABLE `videogallery`
  ADD INDEX `idx_video_tour` (`tour_id`);

ALTER TABLE `notifications`
  ADD INDEX `idx_notification_user` (`user_id`);

ALTER TABLE `multimedia`
  ADD INDEX `idx_media_tour` (`tour_id`);

COMMIT;