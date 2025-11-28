Software Requirements Specification (SRS) for GrowDev
Version: 1.0
Date: October 26, 2023
GitHub Repository: https://github.com/Shoaibvai/growdev

## Project Pods & Assignments
GrowDev organizes contributors around active project streams rather than generic teams. Each pod owns a slice of the functional (FR) and non-functional (NFR) backlog, and every task captures current status so work can be tracked directly from this SRS.

### Project Atlas — Identity & Access
- **Members:** Shoaib Ibna Omar (Lead), Sejanul Islam
- **Scope:** FR1 (User Management), FR3 (Project Staffing), NFR4–NFR7 (security & reliability)
- **Current Tasks:**
	- `FR1.1–FR1.4` authentication flow refactor — _Owner: Shoaib_ — **Status:** ✅ Complete
	- `FR3.2` searchable project-member invite wizard — _Owner: Sejanul_ — **Status:** ✅ Complete
	- `NFR4/NFR7` periodic security audit automation — _Owner: Shoaib_ — **Status:** 🔵 Planned

### Project Helix — Documentation Workspace
- **Members:** Mansura Yeasmin (Lead), Shafin Foysal
- **Scope:** FR2 (Project Management dashboards), FR5 (Documentation), NFR11–NFR13 (usability/accessibility)
- **Current Tasks:**
	- `FR2.5/FR2.6` project overview cards linked to requirement status — _Owner: Mansura_ — **Status:** ✅ Complete
	- `FR5.1–FR5.6` SRS template polish & PDF export QA — _Owner: Shafin_ — **Status:** ✅ Complete
	- `NFR11` responsive layout audit for documentation editor — _Owner: Shafin_ — **Status:** 🔵 Planned

### Project Pulse — Collaboration & Delivery
- **Members:** Ismail Hossain (Lead), Sejanul Islam
- **Scope:** FR4 (Task Allocation), FR6 (Email Notifications), FR7 (Data Sync), upcoming `FR8` (User Discovery), plus NFR8–NFR10 (availability, backups)
- **Current Tasks:**
	- `FR4.1–FR4.5` task board with assignment + status chips — _Owner: Ismail_ — **Status:** ✅ Complete
	- `FR6.1–FR6.5` reminder digest + preference center — _Owner: Sejanul_ — **Status:** 🔵 Planned
	- `FR7.1–FR7.4` replica sync health monitoring — _Owner: Ismail_ — **Status:** ✅ Complete

> **Status Legend:** ✅ Complete · 🟡 In Progress · 🔵 Planned

1. Introduction
1.1 Purpose
This document outlines the requirements for GrowDev, a project management application designed to guide users through software development processes. The application will support both solo developers and teams, providing role-based task allocation, documentation templates, email notifications, and a shared database for collaboration.
1.2 Scope
GrowDev will:
Guide users through software development lifecycle phases
Support different project types (solo and team-based)
Enable role-based task allocation to team members
Provide documentation templates for project artifacts
Include email notification features for team communication
Maintain a shared database accessible across devices
1.3 Definitions, Acronyms, and Abbreviations
SRS: Software Requirements Specification
UI: User Interface
UX: User Experience
CRUD: Create, Read, Update, Delete
API: Application Programming Interface
1.4 References
IEEE Std 830-1998 - IEEE Recommended Practice for Software Requirements Specifications
GitHub Repository: https://github.com/Shoaibvai/growdev
1.5 Overview
This document is organized into sections covering:
Overall product description
Specific functional and non-functional requirements
Interface requirements
Appendices with supporting information

2. Overall Description
2.1 Product Perspective
GrowDev is a web-based application that will function as a Software as a Service (SaaS) platform. It will consist of:
Frontend interface accessible through web browsers
Backend server for business logic processing
Database for data persistence
Email service integration for notifications
API for potential third-party integrations
2.2 Product Functions
2.2.1 Development Process Guidance
Provide step-by-step guidance through software development phases:
Requirements gathering
Design
Implementation
Testing
Deployment
Maintenance
Offer templates and best practices for each phase
Track progress through each development stage
2.2.2 Project Type Support
Support for solo development projects
Support for multi-member project workspaces with:
- Project-centric member rosters instead of ad-hoc teams
- Global user search to discover and shortlist contributors
- Role definitions tied to functional/non-functional requirements
- Permission levels that inherit from project roles
2.2.3 Role-Based Task Allocation
Create customizable roles (e.g., Developer, UI Designer, Tester)
Assign specific development aspects to team members:
Backend development
Frontend development
UI/UX design
Database design
Testing
Documentation
Allow multiple assignments per team member (e.g., Developer X assigned to both backend and UI tasks)
Visual representation of task assignments
2.2.4 Documentation Templates
Provide templates for:
Software Requirements Specification (SRS)
Test plans
User manuals
Meeting notes
Project charters
Allow customization of templates
Enable export to common formats (PDF, DOCX)
2.2.5 Email Notification System
Send automated emails for:
Task assignments
Deadline reminders
Status updates
Meeting invitations
Allow customization of email content
Support email templates for different scenarios
2.2.6 Shared Database
Centralized data storage accessible to all team members
Real-time synchronization across devices
Version history for documents and project data
Access control based on user roles

3. Specific Requirements
3.1 Functional Requirements
3.1.1 User Management
FR1.1: Users shall be able to register and create accounts
FR1.2: Users shall be able to log in and log out securely
FR1.3: Users shall be able to reset forgotten passwords
FR1.4: Users shall be able to update their profile information
FR1.5: System shall support role-based user permissions
3.1.2 Project Management
FR2.1: Users shall be able to create new projects
FR2.2: Users shall be able to specify project type (solo or team)
FR2.3: Users shall be able to define project scope and objectives
FR2.4: Users shall be able to set project timelines and milestones
FR2.5: System shall track project progress through development phases
FR2.6: Users shall be able to view project dashboards with status summaries
3.1.3 Project Staffing & Assignment
FR3.1: Project managers shall be able to create and manage project member rosters
FR3.2: Project managers shall be able to search the user directory and invite members via email
FR3.3: Project managers shall be able to define custom project roles mapped to functional/non-functional requirements
FR3.4: Project managers shall be able to assign roles and link open tasks (e.g., FR4, NFR8) to specific project members
FR3.5: Invitees shall be able to accept or decline project membership requests
3.1.4 Task Allocation
FR4.1: Project managers shall be able to create tasks for development aspects
FR4.2: Project managers shall be able to assign tasks to specific team members
FR4.3: Project managers shall be able to assign multiple aspects to the same team member
FR4.4: Team members shall be able to view their assigned tasks
FR4.5: Team members shall be able to update task status
FR4.6: System shall provide visual representation of task assignments
3.1.5 Documentation
FR5.1: System shall provide templates for common project documents
FR5.2: Users shall be able to create documents from templates
FR5.3: Users shall be able to customize document templates
FR5.4: Users shall be able to collaborate on documents in real-time
FR5.5: Users shall be able to export documents in multiple formats
FR5.6: System shall maintain version history for documents
3.1.6 Email Notifications
FR6.1: System shall send email notifications for task assignments
FR6.2: System shall send email reminders for upcoming deadlines
FR6.3: System shall send email notifications for status updates
FR6.4: Users shall be able to customize email notification preferences
FR6.5: System shall provide email templates for different scenarios
3.1.7 Data Sharing and Synchronization
FR7.1: System shall maintain a shared database for all project data
FR7.2: System shall synchronize data across all user devices in real-time
FR7.3: System shall provide offline access with synchronization when online
FR7.4: System shall maintain access logs for data security

3.1.8 User Discovery & Task Assignment
FR8.1: Project managers shall be able to query users by name, skill, requirement tag (FR/NFR), or availability before assignment
FR8.2: The system shall display a consolidated view of each project member, their active tasks, and associated requirement references
FR8.3: Managers shall be able to assign or reassign tasks directly from the user search results to keep projects staffed efficiently
FR8.4: Every task UI shall expose status controls (e.g., Planned, In Progress, Complete) that roll up into project dashboards
FR8.5: Task status changes shall trigger notifications and audit logs identifying the responsible member and linked requirement IDs

3.2 Non-Functional Requirements
3.2.1 Performance
NFR1: System shall respond to user actions within 2 seconds under normal load
NFR2: System shall support up to 100 concurrent users without performance degradation
NFR3: System shall handle document uploads of up to 10MB within 5 seconds
3.2.2 Security
NFR4: System shall use secure authentication (OAuth 2.0 or JWT)
NFR5: System shall encrypt sensitive data at rest and in transit
NFR6: System shall implement role-based access control
NFR7: System shall conduct regular security audits
3.2.3 Reliability
NFR8: System shall be available 99.9% of the time
NFR9: System shall have automated backups every 24 hours
NFR10: System shall have disaster recovery procedures
3.2.4 Usability
NFR11: System shall have an intuitive user interface requiring minimal training
NFR12: System shall be accessible to users with disabilities (WCAG 2.1 compliant)
NFR13: System shall provide contextual help and tooltips
3.2.5 Compatibility
NFR14: System shall be compatible with the latest versions of Chrome, Firefox, Safari, and Edge
NFR15: System shall be responsive and functional on mobile devices
NFR16: System shall function on Windows, macOS, and Linux operating systems
3.3 External Interface Requirements
3.3.1 User Interfaces
Web-based interface with responsive design
Dashboard with project overview and navigation
Forms for project and task creation
Document editor with collaboration features
Visual task allocation interface
3.3.2 Software Interfaces
Integration with email service providers (SendGrid, Mailgun)
Potential integration with version control systems (GitHub, GitLab)
API for third-party integrations
3.3.3 Hardware Interfaces
Standard input devices (keyboard, mouse, touchscreens)
Output devices (monitors, printers)
Network connectivity for data synchronization
3.3.4 Communications Interfaces
HTTPS for secure web communication
RESTful API for client-server communication
WebSocket for real-time collaboration features

4. Appendices
Appendix A: Use Cases
Use Case 1: Creating a New Project
Use Case 2: Setting Up a Team
Use Case 3: Assigning Development Tasks
Use Case 4: Creating Documentation
Use Case 5: Sending Email Notifications
Appendix B: Data Dictionary
Detailed description of all data entities and attributes
Appendix C: Mockups and Wireframes
Initial design concepts for key interfaces
