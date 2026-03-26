# 📦 Leaf PHP

A lightweight microservices architecture built using **native PHP (no frameworks)**.  
This project is designed for learning how backend systems work internally—routing, request handling, and service separation.

---

## 🚀 Project Overview

Each service is an independent PHP application with:

- A single entry point (`index.php`)
- A simple Router
- A Controller for handling endpoints
- Shared core utilities (Request, Response, Core)

---


## ⚙️ Features

- Custom routing system (GET, POST)
- JSON request/response handling
- Singleton-based router
- Minimal and modular design
- No external frameworks

---


## 🎯 Goals of This Project

Understand backend fundamentals without frameworks
Learn microservice architecture basics
Build a custom lightweight PHP framework
Prepare for scaling into Docker & Kubernetes environments


## ⚙️ Usage Instructions

### Clone the repository

```
git clone git@github.com:coderaticebear/leaf-php.git

```
### Create a New Service

```
php core create-service [service_name]

```

### Run a service

```
php core serve [service_name] [port]

```

### Help

```
php core help
```