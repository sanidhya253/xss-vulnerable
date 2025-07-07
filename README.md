# xss-vulnerable
# 🐞 HobbyNest – XSS Vulnerable Web Application

A deliberately vulnerable web platform designed for demonstrating **Stored Cross-Site Scripting (XSS)** and **session hijacking attacks** in a controlled lab environment.

## 🧠 Project Overview

**HobbyNest** is a mock social platform where users can register, log in, and share their hobbies. The platform is intentionally left vulnerable to stored XSS through the **hobby submission form**, allowing attackers to inject malicious scripts and hijack user sessions.

## ⚠️ Key Vulnerability

- **Stored XSS (Cross-Site Scripting)**
- No input sanitization or output encoding in the "Hobby" field
- Session ID stolen via `document.cookie` and sent to attacker's server
- Attacker uses victim's session ID to hijack their session and access their dashboard

---

## 🏗️ Features

- User Registration & Login
- Add/View hobbies
- User Dashboard with persistent session
- Exploitable hobby field (XSS)
- Session hijacking simulation

---

## 🔥 Attack Flow

1. Attacker registers and submits a hobby with a **malicious JS payload**.
2. Victim logs in and views the attacker’s hobby.
3. The payload executes silently, sending the victim’s `document.cookie` to the attacker.
4. Attacker extracts the **PHPSESSID** and uses it to impersonate the victim.

---

## ⚙️ Setup Instructions

### Requirements:
- [XAMPP]
- PHP & MySQL

### Steps:

1. Clone this repository:
   git clone (https://github.com/sanidhya253/xss-vulnerable)
