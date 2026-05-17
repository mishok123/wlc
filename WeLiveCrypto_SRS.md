
# Software Requirements Specification – WeLiveCrypto Directory

## 1. Introduction

### 1.1 Purpose
This document defines the Software Requirements Specification (SRS) for the **WeLiveCrypto Directory Listing Platform**, including its integration with the **SMF (Simple Machines Forum)**.  
The SRS serves as a reference for stakeholders, developers, designers, and testers involved in the design, development, and maintenance of the system.

### 1.2 Scope
The system is a web-based crypto project directory hosted at **welivecrypto.com**, providing structured listings, filters, reviews, and status indicators for crypto-related services such as exchanges, mixers, and wallets.  
The platform integrates seamlessly with an SMF forum, sharing authentication, session management, and visual design to provide a unified user experience.

### 1.3 Definitions, Acronyms, and Abbreviations
- **SMF**: Simple Machines Forum  
- **KYC**: Know Your Customer  
- **AML**: Anti-Money Laundering  
- **LoG**: Letter of Guarantee  
- **TOR**: The Onion Router  

### 1.4 References
- SMF Forum: https://download.simplemachines.org/
- Example sites: kycnot.me, bitlist.co, mixer.alttstats.casa
- UI Theme Reference: Tradexy ThemeForest Template

### 1.5 Overview
This document describes overall system functionality, user roles, functional requirements, non-functional requirements, constraints, and development phases.

---

## 2. Overall Description

### 2.1 Product Perspective
The WeLiveCrypto Directory is a standalone web application tightly integrated with an SMF forum.  
Both applications will:
- Share the same user session (single sign-on)
- Share a unified graphical interface
- Appear to users as a single application

### 2.2 Product Functions
- Crypto project directory listing
- Advanced filtering and categorization
- Project detail pages
- User reviews and ratings
- Automatic online/offline status detection
- SMF forum integration
- Admin moderation and verification

### 2.3 User Classes and Characteristics

#### Guest Users
- Browse project listings and detail pages
- Use public filters and search
- View reviews, ratings, and trust indicators

#### Registered Users
- All guest capabilities
- Submit new project listings
- Leave reviews and star ratings
- Report or flag projects
- Save searches and create watchlists
- Participate in SMF forum discussions

#### Project Owners
- All registered user capabilities
- Claim and manage ownership of projects
- Request ownership verification
- Update project information (subject to moderation)

#### Moderators / Administrators
- Approve, reject, or modify listings
- Moderate reviews, reports, and forum links
- Verify ownership and project data
- Assign list status (Proposed, Approved, Verified, Scam)
- Manage badges, featured listings, and automated checks

### 2.4 Operating Environment
- Web-based application
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Server-side integration with SMF (PHP-based)

### 2.5 Design and Implementation Constraints
- SMF must remain functionally intact
- UI must be adapted to SMF, not vice versa
- High emphasis on privacy and security

---

## 3. Functional Requirements

### 3.1 User Authentication & Session Management
- Shared login session between WeLiveCrypto and SMF
- Automatic authentication across both systems
- Optional two-factor authentication (2FA)

### 3.2 Directory Listings

#### 3.2.1 Supported Project Categories
- Exchanges (CEX / DEX)
- Mixers
- Wallets
- Casinos & Sportsbooks
- Cards & Payment Services
- DeFi Protocols
- NFT Marketplaces
- Analytics & Tools
- Trading Bots
- Launchpads
- Other (XYZ)

#### 3.2.2 General Attributes (All Projects)
- Industry / Category
- Project Age
- Online Status (automated)
- Supported Cryptocurrencies
- Supported Networks (Clear Net, TOR, P2P)
- Features (API, Telegram Bot, etc.)
- Official Discussion Channels
- Privacy Level (KYC)
- AML Risk Level
- Regulatory Status
- Jurisdiction / Region (Optional)
- Security Features
- User Review Count
- User Star Rating
- Community Trust Score
- List Status
- Ownership Verified
- Registration Required
- No-Log Policy
- Source Code Availability
- Own Liquidity

#### 3.2.3 Mixer & Exchange Specific Attributes
- Minimum Service Fees (%)
- Additional Fixed Fees
- Minimum Time Delay
- Letter of Guarantee (LoG)
- Withdrawal Fees (Exchange only)
- Liquidity Indicator

#### 3.2.4 Wallet-Specific Attributes
- Custodial Type
- Wallet Name

### 3.3 Filtering, Search & Comparison
- Advanced multi-attribute filtering
- Side-by-side comparison
- Saved searches and watchlists

### 3.4 Project Entry & Management
- Project submission via dynamic form
- Default status: Proposed
- Ownership verification requests
- Full change logs for edits

### 3.5 Reviews, Ratings & Community Signals
- User reviews and ratings
- Review upvoting and flagging
- Community scam/suspicion flags

### 3.6 Automation & Monitoring
- Live URL health checks
- Online/offline status updates
- Automated trust & risk scoring
- Metadata extraction

### 3.7 Badges, Trust Marks & Monetization
- Verified badges with do-follow links
- Featured / sponsored listings
- Affiliate link tracking

### 3.8 SMF Forum Integration
- Unified UI/UX
- Linked discussion threads
- Shared identity and permissions

---

## 4. Non-Functional Requirements

### 4.1 Performance
- Page load time under 3 seconds

### 4.2 Security
- Secure authentication and sessions
- Protection against XSS, CSRF, SQL Injection

### 4.3 Usability
- Consistent UI/UX
- Mobile-responsive design

### 4.4 Scalability
- Support thousands of listings and concurrent users

### 4.5 Reliability
- Fault-tolerant uptime monitoring

---

## 5. Development Phases

### Phase 1
- Homepage layout
- Project listing and detail pages

### Phase 2
- SMF UI integration
- Shared authentication and sessions

### Phase 3
- Project entry forms
- Filters, reviews, and automation

---

## 6. Future Enhancements
- Mediation service with case tracking
- Evidence and message exchange
- Status indicators and admin tools
- Trust indicators from mediation participation
- Advanced analytics
- Public API access
- Community-driven verification

---

## 7. Appendix
- UI inspirations
- Example directory platforms
