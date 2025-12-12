# Software Requirements Specification (SRS)

## Library Management System

---

## 1. Introduction

### 1.1 Purpose

เอกสารนี้จัดทำขึ้นเพื่อระบุข้อกำหนดและความต้องการของระบบจัดการห้องสมุด (Library Management System) ที่ครอบคลุมการยืม-คืนหนังสือ การจัดการสมาชิก และการรายงานผลต่างๆ

### 1.2 Scope

ระบบจัดการห้องสมุดเป็นระบบ Web-based Application ที่พัฒนาเพื่อรองรับการทำงานของห้องสมุด โดยมีกลุ่มผู้ใช้งาน 3 กลุ่ม ได้แก่:

- **Administrator (ผู้ดูแลระบบ)** - จัดการผู้ใช้งาน ตั้งค่าระบบ และดูรายงานภาพรวม
- **Librarian (บรรณารักษ์)** - จัดการการยืม-คืนหนังสือ จัดการข้อมูลสมาชิกและหนังสือ
- **Member (สมาชิก)** - ค้นหาหนังสือ จองหนังสือ ดูประวัติการยืม และชำระค่าปรับ

### 1.3 Definitions, Acronyms, and Abbreviations

- **SRS**: Software Requirements Specification
- **UI**: User Interface
- **API**: Application Programming Interface
- **CRUD**: Create, Read, Update, Delete
- **ISBN**: International Standard Book Number

---

## 2. Overall Description

### 2.1 Product Perspective

ระบบจัดการห้องสมุดเป็นระบบแบบ standalone ที่สามารถทำงานได้อย่างอิสระ โดยมีการเชื่อมต่อกับฐานข้อมูลสำหรับจัดเก็บข้อมูล และระบบอีเมลสำหรับการแจ้งเตือน

### 2.2 Product Functions

ฟังก์ชันหลักของระบบประกอบด้วย:

1. ระบบจัดการผู้ใช้งานและสิทธิ์การเข้าถึง
2. ระบบจัดการข้อมูลหนังสือและหมวดหมู่
3. ระบบยืม-คืนหนังสือ
4. ระบบค้นหาและจองหนังสือ
5. ระบบคำนวณและจัดการค่าปรับ
6. ระบบรายงานและสถิติ
7. ระบบแจ้งเตือน

### 2.3 User Classes and Characteristics

| User Class    | Characteristics                       | Technical Expertise |
| ------------- | ------------------------------------- | ------------------- |
| Administrator | มีสิทธิ์เต็มในการจัดการระบบทั้งหมด    | ปานกลาง-สูง         |
| Librarian     | ใช้งานระบบประจำวันในการจัดการห้องสมุด | ปานกลาง             |
| Member        | ผู้ใช้งานทั่วไปที่ต้องการยืมหนังสือ   | พื้นฐาน             |

### 2.4 Operating Environment

- **Client**: Web Browser (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)
- **Server**: Node.js 16+ หรือ Python 3.9+
- **Database**: MySQL 8.0+, PostgreSQL 13+, หรือ MongoDB 5.0+
- **Operating System**: Windows Server 2019+, Linux (Ubuntu 20.04+), หรือ macOS 11+

---

## 3. Functional Requirements

### 3.1 Administrator Functions

#### F-001: Manage Users

**Priority**: High
**Description**: Administrator สามารถจัดการข้อมูลผู้ใช้งานทั้งหมดในระบบ

**Input**:

- User ID, Username, Password, Full Name, Email, Phone Number, Role, Status

**Process**:

1. Administrator เข้าสู่หน้าจัดการผู้ใช้งาน
2. เลือกการดำเนินการ: เพิ่ม/แก้ไข/ลบ/ค้นหาผู้ใช้งาน
3. กรอกข้อมูลผู้ใช้งาน
4. ระบบตรวจสอบความถูกต้องของข้อมูล
5. บันทึกข้อมูลลงฐานข้อมูล

**Output**:

- ข้อความแสดงผลการดำเนินการสำเร็จหรือไม่สำเร็จ
- รายการผู้ใช้งานที่อัพเดท

**Business Rules**:

- Username ต้องไม่ซ้ำกันในระบบ
- Email ต้องอยู่ในรูปแบบที่ถูกต้อง
- Password ต้องมีความยาวอย่างน้อย 8 ตัวอักษร
- ไม่สามารถลบผู้ใช้งานที่กำลังมีหนังสือค้างยืมอยู่

#### F-002: Manage Books

**Priority**: High
**Description**: Administrator สามารถจัดการข้อมูลหนังสือทั้งหมดในระบบ

**Input**:

- ISBN, Title, Author, Publisher, Published Year, Category, Quantity, Location, Cover Image, Description

**Process**:

1. Administrator เข้าสู่หน้าจัดการหนังสือ
2. เลือกการดำเนินการ: เพิ่ม/แก้ไข/ลบ/ค้นหาหนังสือ
3. กรอกข้อมูลหนังสือ
4. อัพโหลดรูปภาพปกหนังสือ (ถ้าต้องการ)
5. ระบบตรวจสอบความถูกต้องของข้อมูล
6. บันทึกข้อมูลลงฐานข้อมูล

**Output**:

- ข้อความแสดงผลการดำเนินการสำเร็จหรือไม่สำเร็จ
- รายการหนังสือที่อัพเดท

**Business Rules**:

- ISBN ต้องไม่ซ้ำกันในระบบ
- Quantity ต้องเป็นจำนวนเต็มบวก
- ไม่สามารถลบหนังสือที่กำลังถูกยืมอยู่
- สามารถปรับสถานะหนังสือเป็น Available, Borrowed, Reserved, Lost, Damaged

#### F-003: Manage Categories

**Priority**: Medium
**Description**: Administrator สามารถจัดการหมวดหมู่หนังสือ

**Input**:

- Category ID, Category Name, Description

**Process**:

1. เข้าสู่หน้าจัดการหมวดหมู่
2. เพิ่ม/แก้ไข/ลบหมวดหมู่
3. บันทึกข้อมูล

**Output**:

- รายการหมวดหมู่ที่อัพเดท

**Business Rules**:

- ชื่อหมวดหมู่ต้องไม่ซ้ำกัน
- ไม่สามารถลบหมวดหมู่ที่มีหนังสืออยู่

#### F-004: View Reports and Statistics

**Priority**: Medium
**Description**: Administrator สามารถดูรายงานและสถิติต่างๆ ของระบบ

**Reports Available**:

1. **รายงานการยืม-คืน**

   - จำนวนการยืมรายวัน/รายเดือน/รายปี
   - หนังสือที่ถูกยืมมากที่สุด
   - สมาชิกที่ยืมบ่อยที่สุด

2. **รายงานค่าปรับ**

   - รายได้จากค่าปรับรายวัน/รายเดือน/รายปี
   - สมาชิกที่มีค่าปรับค้างชำระ

3. **รายงานหนังสือ**

   - หนังสือที่ชำรุด/สูญหาย
   - สถานะหนังสือทั้งหมด
   - หนังสือใหม่ในระบบ

4. **รายงานสมาชิก**
   - จำนวนสมาชิกทั้งหมด
   - สมาชิกใหม่รายเดือน
   - สมาชิกที่หมดอายุ

**Output**:

- รายงานในรูปแบบตาราง/กราฟ
- สามารถ Export เป็น PDF, Excel, CSV

#### F-005: System Configuration

**Priority**: Medium
**Description**: Administrator สามารถตั้งค่าระบบต่างๆ

**Configurable Settings**:

- จำนวนหนังสือสูงสุดที่ยืมได้ต่อคน (default: 5 เล่ม)
- ระยะเวลายืม (default: 14 วัน)
- อัตราค่าปรับต่อวัน (default: 5 บาท)
- จำนวนครั้งที่ต่ออายุได้ (default: 1 ครั้ง)
- ระยะเวลาต่ออายุ (default: 7 วัน)
- ระยะเวลาจอง (default: 3 วัน)
- การแจ้งเตือนทางอีเมล (เปิด/ปิด)
- ภาษาของระบบ (ไทย/อังกฤษ)

**Output**:

- ข้อความยืนยันการบันทึกการตั้งค่า

#### F-006: Backup and Restore Data

**Priority**: High
**Description**: Administrator สามารถสำรองและกู้คืนข้อมูลระบบ

**Process**:

1. เลือกการสำรองข้อมูล (Manual/Automatic)
2. เลือกข้อมูลที่ต้องการสำรอง
3. ระบบสร้างไฟล์สำรองข้อมูล
4. สามารถกู้คืนข้อมูลจากไฟล์สำรอง

**Output**:

- ไฟล์ Backup (.sql, .zip)
- Log การสำรองข้อมูล

---

### 3.2 Librarian Functions

#### F-007: Borrow Books

**Priority**: High
**Description**: Librarian สามารถดำเนินการยืมหนังสือให้กับสมาชิก

**Input**:

- Member ID, Book ID/ISBN, Borrow Date, Due Date

**Process**:

1. Librarian สแกนหรือกรอก Member ID
2. ระบบแสดงข้อมูลสมาชิกและสถานะการยืม
3. ตรวจสอบว่าสมาชิกสามารถยืมได้หรือไม่
4. สแกนหรือกรอก Book ID/ISBN
5. ระบบตรวจสอบสถานะหนังสือ
6. คำนวณวันครบกำหนดคืน
7. บันทึกการยืม
8. พิมพ์ใบยืมหนังสือ

**Output**:

- ใบยืมหนังสือ (Borrow Receipt)
- อัพเดทสถานะหนังสือเป็น "Borrowed"
- บันทึกข้อมูลการยืมในระบบ

**Business Rules**:

- สมาชิกต้องไม่มีค่าปรับค้างชำระ
- สมาชิกยืมได้ไม่เกิน 5 เล่ม
- หนังสือต้องมีสถานะ "Available"
- ถ้ามีการจองหนังสือไว้ ต้องยืมให้ผู้จองก่อน

#### F-008: Return Books

**Priority**: High
**Description**: Librarian สามารถดำเนินการรับคืนหนังสือจากสมาชิก

**Input**:

- Book ID/ISBN, Return Date

**Process**:

1. สแกนหรือกรอก Book ID/ISBN
2. ระบบแสดงข้อมูลการยืม
3. ตรวจสอบสภาพหนังสือ
4. คำนวณค่าปรับ (ถ้ามี)
5. บันทึกการคืน
6. อัพเดทสถานะหนังสือ
7. แจ้งเตือนผู้จอง (ถ้ามี)

**Output**:

- ใบคืนหนังสือพร้อมค่าปรับ (ถ้ามี)
- อัพเดทสถานะหนังสือเป็น "Available"
- บันทึกข้อมูลการคืนในระบบ

**Business Rules**:

- คืนหนังสือเกินกำหนด: คิดค่าปรับวันละ 5 บาท
- หนังสือชำรุด: คิดค่าชดใช้ตามราคาหนังสือ
- หนังสือสูญหาย: คิดค่าชดใช้ 2 เท่าของราคาหนังสือ

#### F-009: Manage Members

**Priority**: High
**Description**: Librarian สามารถจัดการข้อมูลสมาชิก

**Input**:

- Member ID, Name, Address, Phone, Email, Date of Birth, Member Type, Registration Date, Expiry Date

**Process**:

1. เลือกการดำเนินการ: เพิ่ม/แก้ไข/ค้นหาสมาชิก
2. กรอกข้อมูลสมาชิก
3. อัพโหลดรูปถ่าย (ถ้าต้องการ)
4. ระบบตรวจสอบข้อมูล
5. สร้างบัตรสมาชิก

**Output**:

- บัตรสมาชิก (Member Card)
- ข้อมูลสมาชิกในระบบ

**Business Rules**:

- Member ID ต้องไม่ซ้ำกัน
- อายุสมาชิกต้องไม่ต่ำกว่า 7 ปี
- ประเภทสมาชิก: Student, Teacher, General (มีข้อจำกัดการยืมต่างกัน)
- ต้องต่ออายุสมาชิกทุกปี

#### F-010: Search Books

**Priority**: High
**Description**: Librarian สามารถค้นหาหนังสือในระบบ

**Search Criteria**:

- Title (ชื่อหนังสือ)
- Author (ผู้แต่ง)
- ISBN
- Category (หมวดหมู่)
- Publisher (สำนักพิมพ์)
- Keyword (คำค้นหา)

**Search Options**:

- Exact Match (ตรงทั้งหมด)
- Partial Match (ตรงบางส่วน)
- Advanced Search (ค้นหาขั้นสูง)

**Output**:

- รายการหนังสือที่ค้นพบพร้อมสถานะ
- จำนวนหนังสือที่พร้อมให้ยืม
- ตำแหน่งที่เก็บหนังสือ

#### F-011: Record Fine Payment

**Priority**: Medium
**Description**: Librarian สามารถบันทึกการชำระค่าปรับ

**Input**:

- Member ID, Fine Amount, Payment Date, Payment Method

**Process**:

1. ค้นหาสมาชิก
2. แสดงรายการค่าปรับค้างชำระ
3. รับชำระค่าปรับ
4. บันทึกการชำระเงิน
5. พิมพ์ใบเสร็จ

**Output**:

- ใบเสร็จรับเงิน
- อัพเดทสถานะค่าปรับ

**Payment Methods**:

- เงินสด (Cash)
- โอนเงิน (Bank Transfer)
- บัตรเครดิต/เดบิต (Credit/Debit Card)
- พร้อมเพย์ (PromptPay)

---

### 3.3 Member Functions

#### F-012: Search and Browse Books

**Priority**: High
**Description**: สมาชิกสามารถค้นหาและเรียกดูหนังสือในระบบ

**Features**:

- ค้นหาหนังสือตามเกณฑ์ต่างๆ
- เรียกดูหนังสือตามหมวดหมู่
- ดูหนังสือใหม่
- ดูหนังสือยอดนิยม
- ดูรายละเอียดหนังสือ

**Output**:

- รายการหนังสือพร้อมรูปปกและรายละเอียด
- สถานะหนังสือ (Available, Borrowed, Reserved)
- วันที่คาดว่าจะคืน (ถ้าถูกยืม)

#### F-013: Reserve Books

**Priority**: Medium
**Description**: สมาชิกสามารถจองหนังสือที่กำลังถูกยืมอยู่

**Input**:

- Book ID, Member ID, Reservation Date

**Process**:

1. เลือกหนังสือที่ต้องการจอง
2. ระบบตรวจสอบสถานะหนังสือ
3. ยืนยันการจอง
4. ระบบส่งอีเมลยืนยัน

**Output**:

- การยืนยันการจอง
- อีเมลแจ้งเตือนเมื่อหนังสือพร้อมให้ยืม

**Business Rules**:

- จองได้เฉพาะหนังสือที่ถูกยืมอยู่
- รอรับหนังสือได้ 3 วัน หลังจากนั้นยกเลิกอัตโนมัติ
- จองได้ไม่เกิน 3 เล่มต่อครั้ง

#### F-014: View Borrowing History

**Priority**: Medium
**Description**: สมาชิกสามารถดูประวัติการยืมหนังสือของตนเอง

**Information Displayed**:

- หนังสือที่กำลังยืมอยู่ พร้อมวันครบกำหนดคืน
- ประวัติการยืมที่ผ่านมา
- หนังสือที่จองไว้
- สถานะการจอง

**Output**:

- รายการหนังสือที่ยืม/คืน พร้อมวันที่
- จำนวนวันที่เหลือก่อนครบกำหนด

#### F-015: Renew Books

**Priority**: Medium
**Description**: สมาชิกสามารถต่ออายุการยืมหนังสือ

**Input**:

- Borrow ID, Member ID

**Process**:

1. เลือกหนังสือที่ต้องการต่ออายุ
2. ระบบตรวจสอบเงื่อนไขการต่ออายุ
3. ยืนยันการต่ออายุ
4. อัพเดทวันครบกำหนดใหม่

**Output**:

- ยืนยันการต่ออายุ
- วันครบกำหนดคืนใหม่

**Business Rules**:

- ต่ออายุได้เฉพาะหนังสือที่ยังไม่เกินกำหนด
- ต่ออายุได้ 1 ครั้ง (7 วัน)
- ไม่สามารถต่ออายุหนังสือที่มีคนจองไว้

#### F-016: View Fine Information

**Priority**: Medium
**Description**: สมาชิกสามารถดูข้อมูลค่าปรับของตนเอง

**Information Displayed**:

- ค่าปรับทั้งหมดที่ค้างชำระ
- รายละเอียดค่าปรับแต่ละรายการ
- ประวัติการชำระค่าปรับ

**Output**:

- ยอดค่าปรับรวม
- รายละเอียดค่าปรับแต่ละรายการ
- วิธีการชำระค่าปรับ

---

## 4. Non-Functional Requirements

### 4.1 Performance Requirements

- **Response Time**:

  - การค้นหาหนังสือ: < 2 วินาที
  - การยืม-คืนหนังสือ: < 3 วินาที
  - การสร้างรายงาน: < 5 วินาที

- **Throughput**:

  - รองรับผู้ใช้งานพร้อมกันได้อย่างน้อย 100 คน
  - ประมวลผลการยืม-คืนได้อย่างน้อย 50 รายการต่อนาที

- **Database Performance**:
  - รองรับข้อมูลหนังสืออย่างน้อย 100,000 รายการ
  - รองรับข้อมูลสมาชิกอย่างน้อย 10,000 รายการ

### 4.2 Security Requirements

- **Authentication**:

  - รองรับการล็อกอินด้วย Username/Password
  - Session timeout หลังไม่ได้ใช้งาน 30 นาที
  - บังคับเปลี่ยนรหัสผ่านทุก 90 วัน

- **Authorization**:

  - Role-based access control (RBAC)
  - แยกสิทธิ์การเข้าถึงตามบทบาทผู้ใช้

- **Data Security**:

  - เข้ารหัส Password ด้วย bcrypt หรือ Argon2
  - ใช้ HTTPS สำหรับการสื่อสารทั้งหมด
  - Encrypt ข้อมูลส่วนตัวของสมาชิกในฐานข้อมูล

- **Audit Trail**:
  - บันทึก Log การเข้าใช้งานระบบ
  - บันทึกการเปลี่ยนแปลงข้อมูลสำคัญ

### 4.3 Reliability Requirements

- **Availability**: ระบบต้องพร้อมใช้งานอย่างน้อย 99.5% ของเวลา
- **Error Handling**: ระบบต้องแสดงข้อความ error ที่เข้าใจง่าย
- **Data Backup**: สำรองข้อมูลอัตโนมัติทุกวันเวลา 00:00 น.
- **Recovery**: สามารถกู้คืนข้อมูลได้ภายใน 4 ชั่วโมง

### 4.4 Usability Requirements

- **User Interface**:

  - ออกแบบ UI ที่เรียบง่าย ใช้งานง่าย
  - รองรับภาษาไทยและอังกฤษ
  - Responsive Design รองรับทุกขนาดหน้าจอ
  - สอดคล้องกับหลัก UI/UX best practices

- **Accessibility**:

  - รองรับ Screen Reader
  - ขนาดตัวอักษรปรับเปลี่ยนได้
  - ใช้สีที่เหมาะสมสำหรับผู้มีปัญหาการมองเห็น

- **Help & Documentation**:
  - มีคู่มือการใช้งานออนไลน์
  - มี FAQ และ Tutorial videos
  - มีปุ่ม Help ในทุกหน้าจอ

### 4.5 Maintainability Requirements

- **Code Quality**:

  - ใช้ Coding Standards ที่กำหนด
  - Comment และ Documentation ที่ชัดเจน
  - Unit Test Coverage อย่างน้อย 80%

- **Modularity**:

  - แบ่งโค้ดเป็น Module ที่แยกจากกันชัดเจน
  - ใช้ Design Pattern ที่เหมาะสม

- **Version Control**:
  - ใช้ Git สำหรับการควบคุมเวอร์ชัน
  - มี Branch Strategy ที่ชัดเจน

### 4.6 Portability Requirements

- รองรับการติดตั้งบน Windows, Linux, และ macOS
- สามารถ Deploy บน Cloud Platform (AWS, Azure, Google Cloud)
- รองรับ Container (Docker)

---

## 5. Business Rules

### 5.1 Borrowing Rules

| Rule ID | Description                      | Value   |
| ------- | -------------------------------- | ------- |
| BR-001  | จำนวนหนังสือสูงสุดที่ยืมได้ต่อคน | 5 เล่ม  |
| BR-002  | ระยะเวลายืมมาตรฐาน               | 14 วัน  |
| BR-003  | จำนวนครั้งที่ต่ออายุได้          | 1 ครั้ง |
| BR-004  | ระยะเวลาต่ออายุ                  | 7 วัน   |
| BR-005  | ห้ามต่ออายุก่อนวันครบกำหนด       | 3 วัน   |

### 5.2 Fine Rules

| Rule ID | Description                           | Value                 |
| ------- | ------------------------------------- | --------------------- |
| BR-006  | ค่าปรับต่อวัน (คืนเกินกำหนด)          | 5 บาท/วัน             |
| BR-007  | ค่าปรับสูงสุดต่อเล่ม                  | 200 บาท               |
| BR-008  | ค่าชดใช้หนังสือชำรุด                  | ราคาหนังสือ x 1       |
| BR-009  | ค่าชดใช้หนังสือสูญหาย                 | ราคาหนังสือ x 2       |
| BR-010  | ห้ามยืมหนังสือเพิ่มเมื่อมีค่าปรับค้าง | มีค่าปรับค้างชำระ > 0 |

### 5.3 Reservation Rules

| Rule ID | Description                 | Value         |
| ------- | --------------------------- | ------------- |
| BR-011  | จำนวนหนังสือสูงสุดที่จองได้ | 3 เล่ม        |
| BR-012  | ระยะเวลารอรับหนังสือที่จอง  | 3 วัน         |
| BR-013  | ยกเลิกการจองอัตโนมัติ       | หลังครบ 3 วัน |

### 5.4 Member Rules

| Rule ID | Description                 | Value  |
| ------- | --------------------------- | ------ |
| BR-014  | อายุขั้นต่ำในการสมัครสมาชิก | 7 ปี   |
| BR-015  | ระยะเวลาสมาชิกภาพ           | 1 ปี   |
| BR-016  | แจ้งเตือนก่อนบัตรหมดอายุ    | 30 วัน |

### 5.5 Book Status Rules

| Status      | Description     | Can Borrow | Can Reserve |
| ----------- | --------------- | ---------- | ----------- |
| Available   | พร้อมให้ยืม     | ✓          | ✗           |
| Borrowed    | กำลังถูกยืมอยู่ | ✗          | ✓           |
| Reserved    | ถูกจองไว้       | ✗          | ✗           |
| Lost        | สูญหาย          | ✗          | ✗           |
| Damaged     | ชำรุด           | ✗          | ✗           |
| Maintenance | กำลังซ่อมแซม    | ✗          | ✗           |

---

## 6. Use Case Diagrams & Descriptions

### 6.1 Use Case Diagram - Overview

Library Management System

![](https://angsila.cs.buu.ac.th/~wittawas/682/88734365/usecase.png)

### 6.2 Detailed Use Cases

#### UC-001: Borrow Books

**Actor**: Librarian
**Precondition**: Librarian logged in, Member และ Book มีอยู่ในระบบ
**Main Flow**:

1. Librarian เลือกฟังก์ชัน "Borrow Books"
2. ระบบแสดงหน้าจอยืมหนังสือ
3. Librarian สแกน/กรอก Member ID
4. ระบบแสดงข้อมูลสมาชิกและตรวจสอบสิทธิ์การยืม
5. Librarian สแกน/กรอก Book ID
6. ระบบตรวจสอบสถานะหนังสือ
7. Librarian ยืนยันการยืม
8. ระบบบันทึกข้อมูลและพิมพ์ใบยืม

**Alternative Flow**:

- 4a. สมาชิกมีค่าปรับค้างชำระ
  - 4a1. ระบบแจ้งเตือนและไม่อนุญาตให้ยืม
- 4b. สมาชิกยืมครบ 5 เล่มแล้ว
  - 4b1. ระบบแจ้งเตือนและไม่อนุญาตให้ยืม
- 6a. หนังสือไม่พร้อมให้ยืม
  - 6a1. ระบบแจ้งเตือนและเสนอให้จองหนังสือ

**Postcondition**: การยืมถูกบันทึก, สถานะหนังสือเปลี่ยนเป็น "Borrowed"

#### UC-002: Return Books

**Actor**: Librarian
**Precondition**: Librarian logged in, หนังสือถูกยืมอยู่
**Main Flow**:

1. Librarian เลือกฟังก์ชัน "Return Books"
2. Librarian สแกน/กรอก Book ID
3. ระบบแสดงข้อมูลการยืม
4. Librarian ตรวจสอบสภาพหนังสือ
5. ระบบคำนวณค่าปรับ (ถ้ามี)
6. Librarian ยืนยันการคืน
7. ระบบบันทึกข้อมูลและพิมพ์ใบคืน

**Alternative Flow**:

- 5a. คืนหนังสือเกินกำหนด
  - 5a1. ระบบคำนวณค่าปรับ
  - 5a2. แสดงยอดค่าปรับ
- 4a. หนังสือชำรุด
  - 4a1. Librarian เลือก "Damaged"
  - 4a2. ระบบคำนวณค่าชดใช้

**Postcondition**: การคืนถูกบันทึก, สถานะหนังสือเปลี่ยนเป็น "Available"

#### UC-003: Search Books

**Actor**: Member, Librarian
**Precondition**: ผู้ใช้ logged in
**Main Flow**:

1. ผู้ใช้เลือกฟังก์ชัน "Search Books"
2. ระบบแสดงหน้าจอค้นหา
3. ผู้ใช้กรอกเกณฑ์การค้นหา
4. ระบบค้นหาและแสดงผลลัพธ์
5. ผู้ใช้เลือกหนังสือเพื่อดูรายละเอียด
6. ระบบแสดงรายละเอียดหนังสือพร้อมสถานะ

**Alternative Flow**:

- 4a. ไม่พบผลลัพธ์
  - 4a1. ระบบแสดงข้อความ "ไม่พบหนังสือที่ค้นหา"
  - 4a2. เสนอคำแนะนำการค้นหา

**Postcondition**: แสดงรายการหนังสือที่ค้นพบ

---

## 7. Database Schema / ER Diagram

### 7.1 Entity Relationship Diagram

```
+------------------+         +------------------+         +------------------+
|      User        |         |      Book        |         |    Category      |
+------------------+         +------------------+         +------------------+
| PK user_id       |         | PK book_id       |         | PK category_id   |
|    username      |         |    isbn          |         |    name          |
|    password      |         |    title         |         |    description   |
|    full_name     |         |    author        |         +------------------+
|    email         |         |    publisher     |                 |
|    phone         |         |    published_year|                 |
|    role          |         | FK category_id   |<----------------+
|    status        |         |    quantity      |
|    created_at    |         |    available     |
+------------------+         |    location      |
        |                    |    cover_image   |
        |                    |    description   |
        |                    |    status        |
        |                    |    created_at    |
        |                    +------------------+
        |                             |
        |                             |
        |                    +------------------+
        +------------------>|    Borrow        |

                             +------------------+
                             | PK borrow_id     |
                             | FK user_id       |
                             | FK book_id       |
                             |    borrow_date   |
                             |    due_date      |
                             |    return_date   |
                             |    renewed       |
                             |    status        |
                             +------------------+
                                      |
                                      |
                             +------------------+
                             |      Fine        |
                             +------------------+
                             | PK fine_id       |
                             | FK borrow_id     |
                             | FK user_id       |
                             |    amount        |
                             |    reason        |
                             |    paid          |
                             |    paid_date     |
                             |    created_at    |
                             +------------------+

+------------------+ +------------------+
| Reservation | | Transaction |
+------------------+ +------------------+
| PK reservation_id| | PK transaction_id|
| FK user_id | | FK user_id |
| FK book_id | | FK fine_id |
| reserve_date | | amount |
| expiry_date | | payment_method|
| status | | payment_date |
| notified | | reference |
+------------------+ +------------------+

```

### 7.2 Table Definitions

#### Table: users

| Column            | Type         | Constraints                       | Description          |
| ----------------- | ------------ | --------------------------------- | -------------------- |
| user_id           | INT          | PK, AUTO_INCREMENT                | รหัสผู้ใช้งาน        |
| username          | VARCHAR(50)  | UNIQUE, NOT NULL                  | ชื่อผู้ใช้งาน        |
| password          | VARCHAR(255) | NOT NULL                          | รหัสผ่าน (encrypted) |
| full_name         | VARCHAR(100) | NOT NULL                          | ชื่อ-นามสกุล         |
| email             | VARCHAR(100) | UNIQUE, NOT NULL                  | อีเมล                |
| phone             | VARCHAR(20)  |                                   | เบอร์โทรศัพท์        |
| role              | ENUM         | 'admin', 'librarian', 'member'    | บทบาท                |
| status            | ENUM         | 'active', 'inactive', 'suspended' | สถานะ                |
| date_of_birth     | DATE         |                                   | วันเกิด              |
| address           | TEXT         |                                   | ที่อยู่              |
| member_type       | ENUM         | 'student', 'teacher', 'general'   | ประเภทสมาชิก         |
| registration_date | DATE         |                                   | วันที่สมัครสมาชิก    |
| expiry_date       | DATE         |                                   | วันหมดอายุสมาชิก     |
| profile_image     | VARCHAR(255) |                                   | รูปโปรไฟล์           |
| created_at        | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP         | วันที่สร้าง          |
| updated_at        | TIMESTAMP    | ON UPDATE CURRENT_TIMESTAMP       | วันที่แก้ไข          |

#### Table: books

| Column         | Type          | Constraints                 | Description         |
| -------------- | ------------- | --------------------------- | ------------------- |
| book_id        | INT           | PK, AUTO_INCREMENT          | รหัสหนังสือ         |
| isbn           | VARCHAR(20)   | UNIQUE, NOT NULL            | ISBN                |
| title          | VARCHAR(255)  | NOT NULL                    | ชื่อหนังสือ         |
| author         | VARCHAR(255)  | NOT NULL                    | ผู้แต่ง             |
| publisher      | VARCHAR(100)  |                             | สำนักพิมพ์          |
| published_year | INT           |                             | ปีที่พิมพ์          |
| category_id    | INT           | FK                          | หมวดหมู่            |
| quantity       | INT           | NOT NULL, DEFAULT 1         | จำนวนทั้งหมด        |
| available      | INT           | NOT NULL                    | จำนวนที่พร้อมให้ยืม |
| location       | VARCHAR(50)   |                             | ตำแหน่งที่เก็บ      |
| cover_image    | VARCHAR(255)  |                             | รูปปกหนังสือ        |
| description    | TEXT          |                             | คำอธิบาย            |
| price          | DECIMAL(10,2) |                             | ราคา                |
| status         | ENUM          | 'active', 'inactive'        | สถานะ               |
| created_at     | TIMESTAMP     | DEFAULT CURRENT_TIMESTAMP   | วันที่เพิ่ม         |
| updated_at     | TIMESTAMP     | ON UPDATE CURRENT_TIMESTAMP | วันที่แก้ไข         |

#### Table: categories

| Column      | Type         | Constraints               | Description  |
| ----------- | ------------ | ------------------------- | ------------ |
| category_id | INT          | PK, AUTO_INCREMENT        | รหัสหมวดหมู่ |
| name        | VARCHAR(100) | UNIQUE, NOT NULL          | ชื่อหมวดหมู่ |
| description | TEXT         |                           | คำอธิบาย     |
| created_at  | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP | วันที่สร้าง  |

#### Table: borrows

| Column      | Type      | Constraints                       | Description          |
| ----------- | --------- | --------------------------------- | -------------------- |
| borrow_id   | INT       | PK, AUTO_INCREMENT                | รหัสการยืม           |
| user_id     | INT       | FK, NOT NULL                      | รหัสผู้ยืม           |
| book_id     | INT       | FK, NOT NULL                      | รหัสหนังสือ          |
| borrow_date | DATE      | NOT NULL                          | วันที่ยืม            |
| due_date    | DATE      | NOT NULL                          | วันครบกำหนดคืน       |
| return_date | DATE      |                                   | วันที่คืนจริง        |
| renewed     | BOOLEAN   | DEFAULT FALSE                     | ต่ออายุแล้ว          |
| renew_count | INT       | DEFAULT 0                         | จำนวนครั้งที่ต่ออายุ |
| status      | ENUM      | 'borrowed', 'returned', 'overdue' | สถานะ                |
| notes       | TEXT      |                                   | หมายเหตุ             |
| created_at  | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP         | วันที่สร้าง          |
| updated_at  | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP       | วันที่แก้ไข          |

#### Table: fines

| Column         | Type          | Constraints                             | Description |
| -------------- | ------------- | --------------------------------------- | ----------- |
| fine_id        | INT           | PK, AUTO_INCREMENT                      | รหัสค่าปรับ |
| borrow_id      | INT           | FK                                      | รหัสการยืม  |
| user_id        | INT           | FK, NOT NULL                            | รหัสผู้ใช้  |
| amount         | DECIMAL(10,2) | NOT NULL                                | จำนวนเงิน   |
| reason         | VARCHAR(255)  |                                         | เหตุผล      |
| paid           | BOOLEAN       | DEFAULT FALSE                           | ชำระแล้ว    |
| paid_date      | DATE          |                                         | วันที่ชำระ  |
| payment_method | ENUM          | 'cash', 'transfer', 'card', 'promptpay' | วิธีชำระ    |
| created_at     | TIMESTAMP     | DEFAULT CURRENT_TIMESTAMP               | วันที่สร้าง |

#### Table: reservations

| Column         | Type      | Constraints                                   | Description      |
| -------------- | --------- | --------------------------------------------- | ---------------- |
| reservation_id | INT       | PK, AUTO_INCREMENT                            | รหัสการจอง       |
| user_id        | INT       | FK, NOT NULL                                  | รหัสผู้จอง       |
| book_id        | INT       | FK, NOT NULL                                  | รหัสหนังสือ      |
| reserve_date   | DATE      | NOT NULL                                      | วันที่จอง        |
| expiry_date    | DATE      | NOT NULL                                      | วันหมดอายุการจอง |
| status         | ENUM      | 'active', 'fulfilled', 'cancelled', 'expired' | สถานะ            |
| notified       | BOOLEAN   | DEFAULT FALSE                                 | แจ้งเตือนแล้ว    |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP                     | วันที่สร้าง      |

#### Table: transactions

| Column         | Type          | Constraints                             | Description   |
| -------------- | ------------- | --------------------------------------- | ------------- |
| transaction_id | INT           | PK, AUTO_INCREMENT                      | รหัสธุรกรรม   |
| user_id        | INT           | FK, NOT NULL                            | รหัสผู้ใช้    |
| fine_id        | INT           | FK                                      | รหัสค่าปรับ   |
| amount         | DECIMAL(10,2) | NOT NULL                                | จำนวนเงิน     |
| payment_method | ENUM          | 'cash', 'transfer', 'card', 'promptpay' | วิธีชำระ      |
| payment_date   | TIMESTAMP     | NOT NULL                                | วันที่ชำระ    |
| reference      | VARCHAR(100)  |                                         | เลขที่อ้างอิง |
| notes          | TEXT          |                                         | หมายเหตุ      |
| created_at     | TIMESTAMP     | DEFAULT CURRENT_TIMESTAMP               | วันที่สร้าง   |

#### Table: system_settings

| Column        | Type        | Constraints                 | Description    |
| ------------- | ----------- | --------------------------- | -------------- |
| setting_id    | INT         | PK, AUTO_INCREMENT          | รหัสการตั้งค่า |
| setting_key   | VARCHAR(50) | UNIQUE, NOT NULL            | คีย์การตั้งค่า |
| setting_value | TEXT        | NOT NULL                    | ค่าการตั้งค่า  |
| description   | TEXT        |                             | คำอธิบาย       |
| updated_at    | TIMESTAMP   | ON UPDATE CURRENT_TIMESTAMP | วันที่แก้ไข    |

#### Table: audit_logs

| Column     | Type         | Constraints               | Description |
| ---------- | ------------ | ------------------------- | ----------- |
| log_id     | INT          | PK, AUTO_INCREMENT        | รหัส log    |
| user_id    | INT          | FK                        | รหัสผู้ใช้  |
| action     | VARCHAR(100) | NOT NULL                  | การกระทำ    |
| table_name | VARCHAR(50)  |                           | ชื่อตาราง   |
| record_id  | INT          |                           | รหัสข้อมูล  |
| old_value  | TEXT         |                           | ค่าเดิม     |
| new_value  | TEXT         |                           | ค่าใหม่     |
| ip_address | VARCHAR(45)  |                           | IP Address  |
| created_at | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP | วันที่สร้าง |

---

## 8. System Architecture Diagram

### 8.1 High-Level Architecture

```

+-------------------+
| Web Browser |
| (Client Side) |
+-------------------+
|
| HTTPS
|
+-------------------+
| Web Server |
| (Nginx/Apache) |
+-------------------+
|
+-------------------+
| Application Layer |
| (Backend API) |
| |
| - Authentication |
| - Authorization |
| - Business Logic |
| - Validation |
+-------------------+
|
+-------------------+
| Database Layer |
| (MySQL/PostgreSQL)|
+-------------------+
|
+-------------------+
| External Services|
| - Email Service |
| - Backup Service |
+-------------------+

```

### 8.2 Technology Stack

#### Frontend

- **Framework**: React.js / Vue.js / Angular
- **UI Library**: Material-UI / Bootstrap / Tailwind CSS
- **State Management**: Redux / Vuex / Context API
- **HTTP Client**: Axios
- **Form Validation**: Formik / Yup

#### Backend

- **Language**: Node.js (Express) / Python (Django/Flask) / Java (Spring Boot)
- **API Style**: RESTful API
- **Authentication**: JWT (JSON Web Tokens)
- **Validation**: Joi / class-validator
- **File Upload**: Multer / Express-fileupload

#### Database

- **Primary DB**: MySQL 8.0+ / PostgreSQL 13+
- **ORM**: Sequelize / TypeORM / SQLAlchemy
- **Migration**: Knex.js / Alembic

#### DevOps

- **Version Control**: Git
- **CI/CD**: GitHub Actions / GitLab CI / Jenkins
- **Container**: Docker
- **Orchestration**: Docker Compose / Kubernetes
- **Cloud**: AWS / Azure / Google Cloud

#### Testing

- **Unit Test**: Jest / Mocha / PyTest
- **Integration Test**: Supertest / Postman
- **E2E Test**: Cypress / Selenium

---

## 9. API Endpoints Specification

### 9.1 Authentication APIs

#### POST /api/auth/login

- **Description**: เข้าสู่ระบบ
- **Request Body**:

```json
{
  "username": "string",
  "password": "string"
}
```

- **Response**:

```json
{
  "success": true,
  "token": "jwt_token_string",
  "user": {
    "user_id": 1,
    "username": "admin",
    "full_name": "Admin User",
    "role": "admin"
  }
}
```

#### POST /api/auth/logout

- **Description**: ออกจากระบบ
- **Headers**: Authorization: Bearer {token}
- **Response**:

```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### 9.2 User Management APIs

#### GET /api/users

- **Description**: ดึงรายการผู้ใช้งานทั้งหมด
- **Headers**: Authorization: Bearer {token}
- **Query Parameters**: page, limit, search, role
- **Response**:

```json
{
  "success": true,
  "data": [
    {
      "user_id": 1,
      "username": "john_doe",
      "full_name": "John Doe",
      "email": "john@example.com",
      "role": "member",
      "status": "active"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "total_items": 100
  }
}
```

#### POST /api/users

- **Description**: เพิ่มผู้ใช้งานใหม่
- **Headers**: Authorization: Bearer {token}
- **Request Body**:

```json
{
  "username": "string",
  "password": "string",
  "full_name": "string",
  "email": "string",
  "phone": "string",
  "role": "member|librarian|admin",
  "date_of_birth": "YYYY-MM-DD",
  "address": "string"
}
```

#### PUT /api/users/:id

- **Description**: แก้ไขข้อมูลผู้ใช้งาน
- **Headers**: Authorization: Bearer {token}

#### DELETE /api/users/:id

- **Description**: ลบผู้ใช้งาน
- **Headers**: Authorization: Bearer {token}

### 9.3 Book Management APIs

#### GET /api/books

- **Description**: ดึงรายการหนังสือ
- **Query Parameters**: page, limit, search, category, status
- **Response**:

```json
{
  "success": true,
  "data": [
    {
      "book_id": 1,
      "isbn": "9780123456789",
      "title": "Introduction to Programming",
      "author": "John Smith",
      "category": "Computer Science",
      "available": 3,
      "quantity": 5,
      "status": "available"
    }
  ]
}
```

#### GET /api/books/:id

- **Description**: ดึงรายละเอียดหนังสือ

#### POST /api/books

- **Description**: เพิ่มหนังสือใหม่
- **Headers**: Authorization: Bearer {token}

#### PUT /api/books/:id

- **Description**: แก้ไขข้อมูลหนังสือ

#### DELETE /api/books/:id

- **Description**: ลบหนังสือ

### 9.4 Borrowing APIs

#### POST /api/borrows

- **Description**: ยืมหนังสือ
- **Request Body**:

```json
{
  "user_id": 1,
  "book_id": 1,
  "borrow_date": "2024-01-01"
}
```

#### PUT /api/borrows/:id/return

- **Description**: คืนหนังสือ
- **Request Body**:

```json
{
  "return_date": "2024-01-15",
  "condition": "good|damaged|lost"
}
```

#### PUT /api/borrows/:id/renew

- **Description**: ต่ออายุการยืม

#### GET /api/borrows/user/:user_id

- **Description**: ดูประวัติการยืมของสมาชิก

### 9.5 Reservation APIs

#### POST /api/reservations

- **Description**: จองหนังสือ
- **Request Body**:

```json
{
  "user_id": 1,
  "book_id": 1
}
```

#### DELETE /api/reservations/:id

- **Description**: ยกเลิกการจอง

#### GET /api/reservations/user/:user_id

- **Description**: ดูรายการจองของสมาชิก

### 9.6 Fine APIs

#### GET /api/fines/user/:user_id

- **Description**: ดูค่าปรับของสมาชิก

#### POST /api/fines/:id/pay

- **Description**: ชำระค่าปรับ
- **Request Body**:

```json
{
  "amount": 50.0,
  "payment_method": "cash|transfer|card|promptpay"
}
```

### 9.7 Report APIs

#### GET /api/reports/borrowing

- **Description**: รายงานการยืม-คืน
- **Query Parameters**: start_date, end_date, format (json|pdf|excel)

#### GET /api/reports/fines

- **Description**: รายงานค่าปรับ

#### GET /api/reports/popular-books

- **Description**: รายงานหนังสือยอดนิยม

---

## 10. User Interface Mockups

### 10.1 Login Page

- ช่องกรอก Username
- ช่องกรอก Password
- ปุ่ม Login
- ลิงก์ Forgot Password

### 10.2 Dashboard (Admin)

- สถิติภาพรวม (Total Books, Total Members, Active Borrows, Total Fines)
- กราฟการยืม-คืนรายเดือน
- รายการหนังสือยอดนิยม
- รายการสมาชิกใหม่

### 10.3 Book Management Page

- ตารางแสดงรายการหนังสือ
- ปุ่ม Add Book
- ช่องค้นหา และ Filter
- ปุ่ม Edit, Delete สำหรับแต่ละหนังสือ

### 10.4 Borrow/Return Page

- ช่องสแกน/กรอก Member ID
- แสดงข้อมูลสมาชิก
- ช่องสแกน/กรอก Book ID
- แสดงรายการหนังสือที่ยืม
- ปุ่ม Confirm Borrow/Return

### 10.5 Member Portal

- หน้าค้นหาหนังสือ
- แสดงหนังสือที่กำลังยืม
- ประวัติการยืม
- ค่าปรับค้างชำระ
- รายการจอง

---

## 11. Testing Requirements

### 11.1 Unit Testing

- ทดสอบ Business Logic แต่ละฟังก์ชัน
- ทดสอบ Validation
- ทดสอบการคำนวณค่าปรับ
- Code Coverage อย่างน้อย 80%

### 11.2 Integration Testing

- ทดสอบ API Endpoints
- ทดสอบการเชื่อมต่อฐานข้อมูล
- ทดสอบ Authentication และ Authorization

### 11.3 System Testing

- ทดสอบ User Journey ทั้งหมด
- ทดสอบ Use Cases หลัก
- ทดสอบ Business Rules

### 11.4 Performance Testing

- Load Testing (100 concurrent users)
- Stress Testing
- Response Time Testing

### 11.5 Security Testing

- SQL Injection
- XSS (Cross-Site Scripting)
- CSRF (Cross-Site Request Forgery)
- Authentication/Authorization Testing

### 11.6 User Acceptance Testing (UAT)

- ทดสอบกับผู้ใช้จริง
- รวบรวม Feedback
- ปรับปรุงตาม Feedback

---

## 12. Deployment Plan

### 12.1 Development Environment

- Local Development
- Development Database
- Debug Mode Enabled

### 12.2 Staging Environment

- Copy of Production
- Testing Environment
- UAT Environment

### 12.3 Production Environment

- Production Server
- Production Database
- Monitoring และ Logging
- Backup System

### 12.4 Deployment Steps

1. Code Review
2. Testing (Unit, Integration, System)
3. Build Application
4. Deploy to Staging
5. UAT
6. Deploy to Production
7. Smoke Testing
8. Monitor

---

## 13. Maintenance and Support

### 13.1 Regular Maintenance

- Database Backup ทุกวัน
- System Update และ Security Patch
- Performance Monitoring
- Log Analysis

### 13.2 Support Levels

- **Level 1**: User Support (How-to questions)
- **Level 2**: Technical Support (Bug fixes)
- **Level 3**: Development Support (Feature requests)

### 13.3 SLA (Service Level Agreement)

- **Critical**: แก้ไขภายใน 4 ชั่วโมง
- **High**: แก้ไขภายใน 1 วัน
- **Medium**: แก้ไขภายใน 3 วัน
- **Low**: แก้ไขภายใน 1 สัปดาห์

---

## 14. Appendix

### 14.1 Glossary

- **Admin**: ผู้ดูแลระบบ
- **Librarian**: บรรณารักษ์
- **Member**: สมาชิกห้องสมุด
- **ISBN**: International Standard Book Number
- **CRUD**: Create, Read, Update, Delete
- **API**: Application Programming Interface
- **JWT**: JSON Web Token

### 14.2 References

- IEEE Software Requirements Specification Template
- Library Management Best Practices
- Web Application Security Guidelines

### 14.3 Document History

| Version | Date       | Author         | Description          |
| ------- | ---------- | -------------- | -------------------- |
| 1.0     | 2024-01-01 | System Analyst | Initial SRS Document |

---

**End of Document**
