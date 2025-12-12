# Test Plan - Library Management System

## ข้อมูลทั่วไป

**ชื่อโปรเจค:** Library Management System - Educational Version  
**เวอร์ชัน:** 1.0  
**วันที่:** [วันที่สร้าง]  
**ผู้จัดทำ:** [ชื่อผู้เรียน]  
**ผู้ตรวจสอบ:** [ชื่อครู]

---

## 1. บทนำ (Introduction)

### 1.1 วัตถุประสงค์ของ Test Plan

เอกสารนี้จัดทำขึ้นเพื่อ:

- ระบุขอบเขตของการทดสอบ
- กำหนดกลยุทธ์การทดสอบ
- ระบุวัตถุประสงค์และเป้าหมายของการทดสอบ
- ระบุประเภท (types) ของการทดสอบที่จะทำ
- ระบุทรัพยากรที่จำเป็น

### 1.2 ขอบเขตของการทดสอบ (Scope)

**ส่วนที่จะทดสอบ:**

- [ ] Authentication & Authorization (Login/Logout)
- [ ] Books Management (CRUD)
- [ ] Members Management (CRUD)
- [ ] Borrowing & Returning Books
- [ ] Fine Calculation
- [ ] Reports Generation
- [ ] Database Operations
- [ ] Security Issues

**ส่วนที่ไม่ทดสอบ:**

- Frontend UI/UX Design
- Performance & Load Testing
- Browser Compatibility (ให้ใช้ Chrome เท่านั้น)

---

## 2. กลยุทธ์การทดสอบ (Test Strategy)

### 2.1 ประเภทการทดสอบ

| ประเภท                  | คำอธิบาย                                          | ลำดับความสำคัญ |
| ----------------------- | ------------------------------------------------- | -------------- |
| **Functional Testing**  | ทดสอบว่าฟีเจอร์ทำงานตามความต้องการ                | สูง            |
| **Security Testing**    | ทดสอบช่องโหว่ด้านความปลอดภัย (SQL Injection, XSS) | สูง            |
| **Database Testing**    | ทดสอบการเชื่อมต่อและการจัดการฐานข้อมูล            | สูง            |
| **Integration Testing** | ทดสอบการทำงานร่วมกันของส่วนต่างๆ                  | ปานกลาง        |
| **Error Handling**      | ทดสอบการจัดการข้อผิดพลาด                          | ปานกลาง        |

### 2.2 ขั้นตอนการทดสอบ

1. **หน่วยที่ 1: Authentication Testing**

   - ทดสอบ Login ด้วยข้อมูลที่ถูกต้อง
   - ทดสอบ Login ด้วยข้อมูลที่ผิด
   - ทดสอบ SQL Injection ในหน้า Login
   - ทดสอบ Session Management

2. **หน่วยที่ 2: Books Management**

   - ทดสอบการเพิ่มหนังสือ
   - ทดสอบการค้นหา
   - ทดสอบ SQL Injection ในการค้นหา

3. **หน่วยที่ 3: Members Management**

   - ทดสอบการเพิ่มสมาชิก
   - ทดสอบการแก้ไขข้อมูล
   - ทดสอบ SQL Injection

4. **หน่วยที่ 4: Borrowing & Returning**
   - ทดสอบกระบวนการยืมหนังสือ
   - ทดสอบการคืนหนังสือ
   - ทดสอบการคำนวณค่าปรับ

---

## 3. Test Cases (กรณีทดสอบ)

### แบบฟอร์มสำหรับเขียน Test Cases

```
Test Case ID: TC-001
Module: Authentication
Feature: Login
Severity: Critical
Priority: P0

Pre-conditions (เงื่อนไขเบื้องต้น):
- ระบบทำงานปกติ
- มีข้อมูล user ในฐานข้อมูล

Test Steps (ขั้นตอนทดสอบ):
1. เปิด http://localhost:8080/login.php
2. กรอก Username: admin
3. กรอก Password: admin123
4. คลิก Login Button

Expected Result (ผลลัพธ์ที่คาดหวัง):
- เข้าสู่หน้า Dashboard สำเร็จ
- แสดงข้อความ "Welcome: [User Name]"

Actual Result (ผลลัพธ์จริง):
[เขียนผลลัพธ์ที่ได้]

Status: [ ] Pass [ ] Fail [ ] Blocked
```

### ตัวอย่าง Test Cases

#### TC-001: Login ด้วยข้อมูลถูกต้อง

- **Expected:** เข้าสู่ Dashboard สำเร็จ
- **Status:** ☐ Pass ☐ Fail

#### TC-002: Login ด้วย SQL Injection

- **Username:** `' OR '1'='1`
- **Password:** `' OR '1'='1`
- **Expected:** แสดงข้อความ "Invalid username or password" (ป้องกัน SQL Injection)
- **Status:** ☐ Pass ☐ Fail

#### TC-003: Login ด้วยข้อมูลผิด

- **Username:** wronguser
- **Password:** wrongpass
- **Expected:** แสดงข้อความ "Invalid username or password"
- **Status:** ☐ Pass ☐ Fail

#### TC-004: ค้นหาหนังสือด้วย SQL Injection

- **Search Query:** `'; DROP TABLE books; --`
- **Expected:** ค้นหาปกติหรือแสดงข้อความ error (ไม่ลบตาราข้อมูล)
- **Status:** ☐ Pass ☐ Fail

#### TC-005: XSS Attack ในการเพิ่มหนังสือ

- **Title:** `<script>alert('XSS')</script>`
- **Expected:** เก็บข้อมูลที่ปลอดภัย หรือแสดง error (ไม่รันสคริปต์)
- **Status:** ☐ Pass ☐ Fail

#### TC-006: ตรวจสอบ Session Timeout

- **Steps:**
  1. Login เข้าระบบ
  2. ปิด Browser หรือลบ Session
  3. Refresh หน้า
- **Expected:** Redirect ไปหน้า Login
- **Status:** ☐ Pass ☐ Fail

---

## 4. ทรัพยากร (Resources)

### 4.1 สภาพแวดล้อมการทดสอบ (Test Environment)

| ประเด็น         | รายละเอียด       |
| --------------- | ---------------- |
| **OS**          | Windows 10/11    |
| **Browser**     | Chrome (ล่าสุด)  |
| **Server**      | Docker Container |
| **Database**    | MySQL 8.0        |
| **PHP Version** | 8.1              |

### 4.2 ข้อมูล Login สำหรับทดสอบ

| Role      | Username  | Password |
| --------- | --------- | -------- |
| Admin     | admin     | admin123 |
| Librarian | librarian | lib123   |

### 4.3 phpMyAdmin Access

- **URL:** http://localhost:8081
- **Username:** root
- **Password:** root_password

---

## 5. ผลลัพธ์การทดสอบ (Test Results)

### 5.1 สรุปผลการทดสอบ

| Module                   | Total TC | Pass | Fail | Pass % |
| ------------------------ | -------- | ---- | ---- | ------ |
| Authentication           | 3        |      |      |        |
| Security (SQL Injection) | 5        |      |      |        |
| Security (XSS)           | 2        |      |      |        |
| Books Management         | 4        |      |      |        |
| Members Management       | 4        |      |      |        |
| Borrowing & Returning    | 4        |      |      |        |
| **รวม**                  | **22**   |      |      |        |

### 5.2 Bugs ที่พบ

| Bug ID  | Module   | Severity | Status       |
| ------- | -------- | -------- | ------------ |
| BUG-001 | Login    | Critical | [ ] Reported |
| BUG-002 | Search   | High     | [ ] Reported |
| BUG-003 | Add Book | High     | [ ] Reported |
|         |          |          |              |

---

## 6. ข้อสรุป (Conclusion)

### 6.1 ผลสรุปการทดสอบ

**ส่วนที่ผ่านการทดสอบ:**

- [ระบุส่วนที่ทำงานปกติ]

**ส่วนที่พบปัญหา:**

- [ระบุ Bugs ที่พบ]

**การกำหนดลำดับความสำคัญ:**

- Critical: [จำนวน]
- High: [จำนวน]
- Medium: [จำนวน]
- Low: [จำนวน]

### 6.2 คำแนะนำ

1. [ระบุการแก้ไขที่ต้องทำ]
2. [ระบุการทดสอบที่ต้องทำเพิ่มเติม]

---

## 7. เอกสารอ้างอิง (References)

- SRS.md - Software Requirements Specification
- BUG_REPORT_TEMPLATE.md - Template สำหรับเขียน Bug Reports
- README.md - คู่มือการติดตั้ง

---

## 8. ลายเซ็นและอนุมัติ

| ผู้ทำหน้าที่ | ชื่อ | ลายเซ็น | วันที่ |
| ------------ | ---- | ------- | ------ |
| ผู้จัดทำ     |      |         |        |
| ผู้ตรวจสอบ   |      |         |        |

---

**หมายเหตุ:**

- ให้เขียน Test Cases อย่างละเอียด
- บันทึกผลลัพธ์ที่แท้จริงเมื่อทำการทดสอบ
- หากพบ Bugs ให้เขียน Bug Report ตามแบบฟอร์ม BUG_REPORT_TEMPLATE.md
