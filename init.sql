-- Library Management System Database Schema

CREATE TABLE IF NOT EXISTS members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    member_code VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    member_type ENUM('student', 'teacher', 'public') NOT NULL,
    registration_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    max_books INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    isbn VARCHAR(20) UNIQUE,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    publisher VARCHAR(100),
    publication_year INT,
    category VARCHAR(50),
    total_copies INT DEFAULT 1,
    available_copies INT DEFAULT 1,
    shelf_location VARCHAR(20),
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS borrowing (
    borrow_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    fine_amount DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(member_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id)
);

CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'librarian') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample admin user (password: admin123)
INSERT INTO users (username, password, full_name, role) VALUES 
('admin', 'admin123', 'System Administrator', 'admin'),
('librarian', 'lib123', 'Library Staff', 'librarian');

-- Insert sample members
INSERT INTO members (member_code, full_name, email, phone, member_type, registration_date, max_books) VALUES
('M001', 'สมชาย ใจดี', 'somchai@email.com', '081-234-5678', 'student', '2024-01-15', 3),
('M002', 'สมหญิง รักหนังสือ', 'somying@email.com', '082-345-6789', 'student', '2024-01-20', 3),
('M003', 'ดร.วิชัย อาจารย์', 'wichai@email.com', '083-456-7890', 'teacher', '2024-02-01', 5),
('M004', 'นางสาวมะลิ ทั่วไป', 'mali@email.com', '084-567-8901', 'public', '2024-02-10', 2),
('M005', 'นายทดสอบ บั๊กเกอร์', 'test@email.com', '085-678-9012', 'student', '2024-03-01', 3);

-- Insert sample books
INSERT INTO books (isbn, title, author, publisher, publication_year, category, total_copies, available_copies, shelf_location) VALUES
('978-616-123-456-7', 'การเขียนโปรแกรม Python', 'สมศักดิ์ โค้ดดี', 'สำนักพิมพ์ไอที', 2023, 'Computer', 3, 3, 'A-101'),
('978-616-123-456-8', 'โครงสร้างข้อมูล', 'วิชัย อัลกอริทึม', 'สำนักพิมพ์ไอที', 2022, 'Computer', 2, 2, 'A-102'),
('978-616-234-567-8', 'วิศวกรรมซอฟต์แวร์', 'ดร.พัฒนา ซอฟต์แวร์', 'สำนักพิมพ์เทคโนโลยี', 2023, 'Computer', 2, 2, 'A-103'),
('978-616-345-678-9', 'ฐานข้อมูล MySQL', 'สุดาศิริ ดาต้าเบส', 'สำนักพิมพ์ไอที', 2023, 'Computer', 3, 3, 'A-104'),
('978-616-456-789-0', 'การทดสอบซอฟต์แวร์', 'ทดสอบ หาบั๊ก', 'สำนักพิมพ์คุณภาพ', 2024, 'Computer', 2, 1, 'A-105'),
('978-616-567-890-1', 'Harry Potter ฉบับภาษาไทย', 'J.K. Rowling', 'Nanmeebooks', 2020, 'Fiction', 5, 5, 'B-201'),
('978-616-678-901-2', 'เศรษฐศาสตร์พอเพียง', 'ดร.เศรษฐกิจ พอใจ', 'สำนักพิมพ์มหาวิทยาลัย', 2023, 'Economics', 2, 2, 'C-301'),
('978-616-789-012-3', 'ประวัติศาสตร์ไทย', 'ศาสตราจารย์ประวัติ รู้ลึก', 'สำนักพิมพ์ประวัติศาสตร์', 2022, 'History', 3, 3, 'D-401');

-- Insert sample borrowing records (some overdue)
INSERT INTO borrowing (member_id, book_id, borrow_date, due_date, status) VALUES
(1, 5, '2024-10-01', '2024-10-15', 'overdue'),
(2, 1, '2024-10-20', '2024-11-03', 'borrowed'),
(3, 3, '2024-10-25', '2024-11-08', 'borrowed');

-- Insert a returned book record
INSERT INTO borrowing (member_id, book_id, borrow_date, due_date, return_date, fine_amount, status) VALUES
(1, 2, '2024-09-01', '2024-09-15', '2024-09-14', 0.00, 'returned'),
(2, 4, '2024-09-10', '2024-09-24', '2024-09-26', 10.00, 'returned');
