## ✅ AI PROMPT (FULL SYSTEM DESIGN + DEVELOPMENT)

You are a senior software architect and full-stack developer specialized in building enterprise-grade ERP systems. Use **React full-stack architecture.**

The system must be production-ready, modular, scalable, secure, and suitable for a real multi-property business.

I want you to design and implement an **Advanced Property Management System (PMS)** that supports:

1. **Rental Property Management**
2. **Lease Management**
3. **Hotel Management**
4. **Advanced Accounting System**
5. **Maintenance & Repairs System**
6. **Hotel Cleaning / Housekeeping Schedules**
7. **Modern Admin Dashboard**
8. Reports, dashboards, audit logs, and role-based permissions
9. Hotel Website Landing Page with Online Booking

The system must be modular, scalable, and suitable for a real business with multiple properties.

---

# 1. SYSTEM MODULES REQUIRED

## A. Rental & Lease Module

Design a system where:

* A **Property** has many **Units**
* Units can be residential, commercial, or hotel rooms
* A **Tenant** can occupy one or more units
* A tenant can transfer from one unit to another
* Each unit can have:
  * rent price
  * deposit amount
  * rent cycle (monthly, weekly, quarterly)
  * billing rules
* Tenants can be active, inactive, blacklisted, or moved out
* Rent can be partially paid
* Rent can be prepaid
* Rent can be overdue with penalties

### Deposit Handling

When a tenant moves in:

* they pay a **deposit**
* deposit is refundable when tenant moves out
* deposit refund must support:
  * deductions (damages, unpaid rent, utility bills)
  * partial refunds
  * deposit refund approval workflow
  * deposit refund transaction must reflect in accounting ledger

---

## B. Hotel Module

Hotel features must include:

* Room types (Standard, Deluxe, Suite)
* Room pricing based on:
  * weekday/weekend pricing
  * seasonal pricing
  * promotional discounts
* Guest check-in / check-out
* Booking system:
  * reservation status: pending, confirmed, cancelled, checked-in, checked-out
  * booking payments: deposit, partial, full
* Walk-in guests
* Group bookings
* Extra services billed to guest:
  * laundry
  * meals
  * minibar
  * late checkout fee

---

## C. Housekeeping / Cleaning Module

Implement hotel housekeeping:

* Cleaning schedules per room
* Room cleaning statuses:
  * dirty
  * cleaning in progress
  * cleaned
  * inspected
  * out of service
* Assign cleaning tasks to staff
* Track cleaning start/end time
* Cleaning checklist per room type
* Supervisor inspection approval

---

## D. Maintenance Module (Rentals + Hotel)

Maintenance system must support:

* Maintenance requests raised by:
  * tenant
  * receptionist
  * manager
  * staff
* Each request includes:
  * property
  * unit/room
  * category (plumbing, electrical, painting, etc.)
  * priority (low, medium, high, emergency)
  * assigned technician
  * status (open, in progress, completed, cancelled)
* Maintenance workflow must include:
  * spare parts used
  * labor cost
  * vendor invoice
  * internal technician costs
* Maintenance expenses must automatically post into accounting system

---

# 2. ACCOUNTING MODULE (VERY IMPORTANT)

Build a complete accounting system that supports:

* Chart of Accounts (Assets, Liabilities, Equity, Income, Expenses)
* Double-entry bookkeeping
* General Ledger
* Journal Entries (manual + automatic)
* Accounts Receivable (tenants and hotel guests)
* Accounts Payable (vendors/contractors)
* Bank accounts and cash accounts
* Bank reconciliation
* Petty cash management
* Multi-branch support (optional but recommended)

### Accounting Automation Rules

The system must auto-generate accounting entries for:

* rent invoice posting
* rent payment receipts
* tenant deposit received
* tenant deposit refund (with deductions)
* hotel booking payment
* hotel extra services billing
* maintenance expenses
* supplier invoices
* refunds and cancellations

---

# 3. FINANCIAL REPORTS REQUIRED

Implement financial reports:

* Profit & Loss Statement (monthly/yearly)
* Balance Sheet
* Cash Flow Statement
* Trial Balance
* General Ledger report
* AR Aging report
* AP Aging report
* Rent Collection Report per property/unit
* Hotel Occupancy report
* Revenue report (rent vs hotel vs services)
* Deposit liability report (total deposits held vs refunded)
* Maintenance expense report
* Tax/VAT report (optional)

Reports must support filters:

* by property
* by date range
* by tenant
* by unit
* by room type
* by branch/location

---

# 4. USER ROLES & PERMISSIONS

Include role-based access control:

* Super Admin
* Property Manager
* Accountant
* Receptionist (Hotel)
* Maintenance Technician
* Housekeeping Staff
* Auditor (read-only)
* Tenant Portal user (optional)
* Guest Portal user (optional)

Each role must have specific permissions.

---

# 5. CORE DATABASE DESIGN REQUIREMENTS

Generate:

* ERD (Entity Relationship Diagram)
* Full database schema design
* Table structures and relationships

Entities must include:

* properties
* units
* rooms
* tenants
* guests
* leases
* rent invoices
* rent payments
* deposit ledger
* hotel bookings
* housekeeping schedules
* maintenance requests
* vendors
* chart of accounts
* journal entries
* ledger transactions
* bank accounts
* expense tracking

Ensure deposit handling is treated as a **liability account** until refunded.

# 6. WORKFLOW REQUIREMENTS

Define workflows clearly:

* tenant onboarding
* lease agreement generation
* rent invoicing and reminders
* deposit receipt and deposit refund
* hotel reservation to checkout
* housekeeping scheduling
* maintenance request lifecycle
* posting to accounting system
* month-end closing process

---

# 7. OUTPUT REQUIRED FROM YOU

Generate the following deliverables:

### A. System Blueprint

* full module breakdown
* folder structure for modular design
* recommended database schema
* ERD description

### B. Implementation Plan

* step-by-step development roadmap
* milestones for MVP and full version
* recommended dev timeline

### C. Code Generation

Provide:

* migrations
* models
* controllers
* services
* sample API routes
* accounting posting logic examples
* sample reports query logic

### D. Example Use Cases

Provide real-life scenarios:

* tenant pays deposit + rent
* tenant moves out deposit refunded with damage deduction
* hotel guest books, pays deposit, checks in, adds minibar bill, checks out
* maintenance job completed and expense posted to accounting ledger

---

# 9. IMPORTANT BUSINESS RULES

* A tenant deposit must be stored under **Deposit Liability Account**
* Refund reduces liability, deductions become income or expense recovery
* Every financial transaction must generate journal entries
* Payments must support multiple payment methods:
  * cash
  * bank transfer
  * mpesa
  * cheque
* Every action must be auditable (audit trail table)

---

# 10. SYSTEM QUALITY REQUIREMENTS

System must support:

* audit logs
* soft deletes
* multi-property management
* multi-currency (optional)
* backups and restore strategy
* performance optimization
* clean modular code
* unit tests for accounting logic

---

Now start by producing:

1. Full system architecture design
2. Database schema + ERD explanation
3. Modules folder structure
4. Accounting logic design (chart of accounts and deposit liability flow)
5. Step-by-step roadmap
6. Key models/migrations scaffolding

Make sure everything is realistic for a production enterprise PMS.
