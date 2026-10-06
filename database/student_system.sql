CREATE DATABASE IF NOT EXISTS student_system;
USE student_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    password VARCHAR(255),
    role VARCHAR(50),
    student_id INT NULL
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reg_no VARCHAR(100),
    fullname VARCHAR(200),
    email VARCHAR(150),
    department VARCHAR(100)
);

CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(150) NOT NULL,
    department_code VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(200),
    course_code VARCHAR(50)
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    marks INT,
    grade VARCHAR(5),
    gpa FLOAT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

INSERT INTO departments(department_name, department_code) VALUES
('Computer Science', 'CS'),
('Information Technology', 'IT'),
('Business Administration', 'BA');

INSERT INTO students(reg_no, fullname, email, department) VALUES
('STU001', 'Mary Wanjiku', 'mary@example.com', 'Computer Science'),
('STU002', 'Brian Otieno', 'brian@example.com', 'Information Technology');

INSERT INTO courses(course_name, course_code) VALUES
('Database Systems', 'DBS101'),
('Web Development', 'WEB102'),
('Data Structures', 'CSC203');

INSERT INTO results(student_id, course_id, marks, grade, gpa) VALUES
(1, 1, 78, 'A', 4.0),
(1, 2, 66, 'B', 3.0),
(2, 1, 54, 'C', 2.0),
(2, 3, 49, 'D', 1.0);

INSERT INTO users(username, password, role, student_id) VALUES
('admin', 'admin123', 'Administrator', NULL),
('lecturer', 'lecturer123', 'Lecturer', NULL),
('student', 'student123', 'Student', 1);
