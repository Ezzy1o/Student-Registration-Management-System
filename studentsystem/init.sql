-- 1. Admin table
CREATE TABLE `admin` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- 2. Programs table (must be created BEFORE students table due to foreign key)
CREATE TABLE `programs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

-- 3. Students table
CREATE TABLE `students` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `student_id` VARCHAR(20) NOT NULL UNIQUE COMMENT 'Unique identifier for the student',
  `email` VARCHAR(100) UNIQUE,
  `phone` VARCHAR(20),
  `date_of_birth` DATE,
  `address` VARCHAR(255),
  `program_id` INT(11) UNSIGNED NOT NULL,  -- FIXED: Changed 'program_id' to `program_id`
  `year` INT(4) NOT NULL COMMENT 'Current academic year (e.g., 1, 2, 3, 4)',
  `emergency_name` VARCHAR(100),
  `emergency_phone` VARCHAR(20),
  `requirements` TEXT COMMENT 'Specific educational or physical requirements',
  `additional_notes` TEXT,
  `photo` VARCHAR(255) COMMENT 'File path or URL to student photo',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`)  -- Moved foreign key constraint here
);

-- 4. Insert programs
INSERT INTO `programs` (`name`) VALUES 
  ('Diploma in Computer Science'),
  ('Diploma in Business Administration'),
  ('Diploma in Engineering'),
  ('Diploma in Education'),
  ('Diploma in Medicine'),
  ('Diploma in Law'),
  ('Diploma in Pharmacy'),
  ('Diploma in Nursing'),
  ('Diploma in Midwifery');

-- 5. Sample INSERT statement (FIXED: using program_id instead of program)
INSERT INTO `students` (
    `first_name`, 
    `last_name`, 
    `student_id`, 
    `email`, 
    `phone`, 
    `date_of_birth`, 
    `program_id`,  -- FIXED: Changed from `program` to `program_id`
    `year`
) VALUES (
    'Alice', 
    'Johnson', 
    'S10012345', 
    'alice.j@university.edu', 
    '555-0101', 
    '2005-08-15', 
    1,  -- FIXED: Changed from 'Computer Science' to 1 (the ID from programs table)
    2
);
