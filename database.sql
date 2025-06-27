-- Ensure the database exists
CREATE DATABASE IF NOT EXISTS AsgardTour;
USE AsgardTour;

-- Table: Users (Travelers and Admins)
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Traveler', 'Admin') DEFAULT 'Traveler',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Tours
CREATE TABLE IF NOT EXISTS Tours (
    tour_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    rating DECIMAL(3,2) DEFAULT 0.0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Bookings
CREATE TABLE IF NOT EXISTS Bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Confirmed', 'Pending', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (tour_id) REFERENCES Tours(tour_id)
);

-- Table: Reviews
CREATE TABLE IF NOT EXISTS Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (tour_id) REFERENCES Tours(tour_id)
);

-- Table: Multimedia (Images & Videos)
CREATE TABLE IF NOT EXISTS Multimedia (
    media_id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    media_type ENUM('Image', 'Video') NOT NULL,
    media_url TEXT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES Tours(tour_id)
);

-- Table: Blogs
CREATE TABLE IF NOT EXISTS Blogs (
    blog_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    location VARCHAR(255),
    likes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Table: Notifications
CREATE TABLE IF NOT EXISTS Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    type ENUM('Booking', 'Review', 'Milestone', 'Alert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
-- Table: Tours (with dedicated columns for main media)
CREATE TABLE IF NOT EXISTS Tours (
    tour_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    rating DECIMAL(3,2) DEFAULT 0.0,
    main_image VARCHAR(255),      -- Main tour image path
    main_video VARCHAR(255),      -- Uploaded video file path
    video_url VARCHAR(255),       -- External video URL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: GalleryImages (for additional gallery images per tour)
CREATE TABLE IF NOT EXISTS GalleryImages (
    gallery_image_id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES Tours(tour_id)
);

-- Table: VideoGallery (for additional gallery videos per tour)
CREATE TABLE IF NOT EXISTS VideoGallery (
    gallery_video_id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    video_url VARCHAR(255) NOT NULL,
    description TEXT,  -- Optional description for the video
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES Tours(tour_id)
);
