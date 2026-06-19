@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0 d-flex align-items-center">
        <iconify-icon icon="solar:book-linear" class="icon text-lg me-1"></iconify-icon>
        User's Manual
    </h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">User's Manual</li>
    </ul>
</div>

<div class="row g-3">
    {{-- Table of Contents Sidebar --}}
    <div class="col-lg-3 d-none d-lg-block">
        <div class="card position-sticky" style="top: 90px;">
            <div class="card-header">
                <h5 class="card-title mb-0">Contents</h5>
            </div>
            <div class="card-body p-0">
                <nav class="manual-toc">
                    <ul class="list-unstyled mb-0">
                        <li><a href="#overview" class="toc-link active">1. System Overview</a></li>
                        <li><a href="#login" class="toc-link">2. Logging In</a></li>
                        <li><a href="#dashboard" class="toc-link">3. Dashboard</a></li>
                        <li><a href="#guest-docs" class="toc-link">4. Guest Documents</a></li>
                        <li><a href="#create-doc" class="toc-link">5. Creating Documents</a></li>
                        <li><a href="#incoming" class="toc-link">6. Incoming Documents</a></li>
                        <li><a href="#pending" class="toc-link">7. Pending Documents</a></li>
                        <li><a href="#forwarding" class="toc-link">8. Forwarding Documents</a></li>
                        <li><a href="#deferred" class="toc-link">9. Deferred Documents</a></li>
                        <li><a href="#batch-release" class="toc-link">10. Batch Release</a></li>
                        <li><a href="#my-documents" class="toc-link">11. My Documents</a></li>
                        <li><a href="#my-section" class="toc-link">12. My Section</a></li>
                        <li><a href="#qr-codes" class="toc-link">13. QR Code Tracking</a></li>
                        <li><a href="#search" class="toc-link">14. Search</a></li>
                        <li><a href="#profile" class="toc-link">15. Profile Settings</a></li>
                        <li><a href="#admin" class="toc-link">16. Administration</a></li>
                        <li><a href="#statuses" class="toc-link">17. Status Reference</a></li>
                        <li><a href="#faq" class="toc-link">18. FAQ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    {{-- Manual Content --}}
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body manual-content">

                {{-- 1. System Overview --}}
                <section id="overview" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="solar:info-circle-linear" class="icon"></iconify-icon>
                        1. System Overview
                    </h4>
                    <p>The <strong>Document Tracking System (DTS)</strong> is a web-based application designed for the Department of Education (DepEd) to manage and track the flow of documents across different sections and offices. It provides a transparent and organized way to handle document routing, receipt, forwarding, and archiving.</p>

                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon bg-primary-50 text-primary-600">
                                <iconify-icon icon="fluent:document-arrow-right-20-filled"></iconify-icon>
                            </div>
                            <h6>Document Routing</h6>
                            <p>Forward documents between sections with full tracking</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon bg-success-50 text-success-600">
                                <iconify-icon icon="mdi:qrcode-scan"></iconify-icon>
                            </div>
                            <h6>QR Code Tracking</h6>
                            <p>Scan QR codes for quick document receipt and lookup</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon bg-warning-50 text-warning-600">
                                <iconify-icon icon="et:documents"></iconify-icon>
                            </div>
                            <h6>Batch Release</h6>
                            <p>Group multiple documents for organized release</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon bg-danger-50 text-danger-600">
                                <iconify-icon icon="solar:chart-2-linear"></iconify-icon>
                            </div>
                            <h6>Reports & Stats</h6>
                            <p>View section statistics and document analytics</p>
                        </div>
                    </div>

                    <h5>Document Lifecycle</h5>
                    <div class="lifecycle-flow">
                        <div class="lifecycle-step">
                            <span class="step-number">1</span>
                            <span class="step-label">Created</span>
                        </div>
                        <div class="lifecycle-arrow">
                            <iconify-icon icon="mdi:arrow-right"></iconify-icon>
                        </div>
                        <div class="lifecycle-step">
                            <span class="step-number">2</span>
                            <span class="step-label">Forwarded</span>
                        </div>
                        <div class="lifecycle-arrow">
                            <iconify-icon icon="mdi:arrow-right"></iconify-icon>
                        </div>
                        <div class="lifecycle-step">
                            <span class="step-number">3</span>
                            <span class="step-label">Received</span>
                        </div>
                        <div class="lifecycle-arrow">
                            <iconify-icon icon="mdi:arrow-right"></iconify-icon>
                        </div>
                        <div class="lifecycle-step">
                            <span class="step-number">4</span>
                            <span class="step-label">Acted Upon</span>
                        </div>
                    </div>
                    <p class="text-secondary-light text-sm mt-2">At step 4, documents can be <strong>Kept/Filed</strong>, <strong>Released</strong>, <strong>Forwarded</strong> to another section, or <strong>Deferred</strong> for later action.</p>
                </section>

                <hr class="section-divider">

                {{-- 2. Logging In --}}
                <section id="login" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="solar:lock-password-linear" class="icon"></iconify-icon>
                        2. Logging In
                    </h4>
                    <ol>
                        <li>Open your web browser and navigate to the DTS URL provided by your administrator.</li>
                        <li>Enter your <strong>Email Address</strong> and <strong>Password</strong> on the Sign-In page.</li>
                        <li>Click the <strong>Sign In</strong> button.</li>
                        <li>You will be redirected to your Dashboard.</li>
                    </ol>

                    <div class="alert alert-info-custom">
                        <iconify-icon icon="solar:info-circle-linear" class="icon"></iconify-icon>
                        <div>
                            <strong>Tip:</strong> If you don't have an account, contact your system administrator. Guest users can submit documents via the <strong>Guest-DTS</strong> link on the login page.
                        </div>
                    </div>
                </section>

                <hr class="section-divider">

                {{-- 3. Dashboard --}}
                <section id="dashboard" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="fluent:home-20-regular" class="icon"></iconify-icon>
                        3. Dashboard
                    </h4>
                    <p>After logging in, you will see the main Dashboard with the following areas:</p>

                    <div class="info-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:30%">Area</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Sidebar Navigation</strong></td>
                                    <td>Left-side menu to access all DTS features. Badge counts show pending items.</td>
                                </tr>
                                <tr>
                                    <td><strong>Top Navbar</strong></td>
                                    <td>Contains search bar, notifications, theme toggle (light/dark), and profile dropdown.</td>
                                </tr>
                                <tr>
                                    <td><strong>QR Scanner</strong></td>
                                    <td>Quick-receipt input for scanning document QR codes / tracking codes.</td>
                                </tr>
                                <tr>
                                    <td><strong>Section Switcher</strong></td>
                                    <td>Green button on each page showing your current section. Click to switch between assigned sections.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5>Switching Sections</h5>
                    <p>If you are assigned to multiple sections, click the section name button (top-right of the content area) and select the section you want to work in. This changes the documents visible to you.</p>
                </section>

                <hr class="section-divider">

                {{-- 4. Guest Documents --}}
                <section id="guest-docs" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="fluent:document-person-20-regular" class="icon"></iconify-icon>
                        4. Guest Documents
                    </h4>
                    <p>Guest documents are submitted by people who do not have a DTS account (walk-in clients, external offices, etc.).</p>

                    <h5>For Guest Users (No Account)</h5>
                    <ol>
                        <li>From the Sign-In page, click the <strong>Guest-DTS</strong> link.</li>
                        <li>Fill in the form:
                            <ul>
                                <li><strong>Guest Name (From)</strong> - Your full name</li>
                                <li><strong>Organization/Office From</strong> - Where you are from</li>
                                <li><strong>Document Type</strong> - Select the appropriate type</li>
                                <li><strong>Description</strong> - Brief description of the document</li>
                                <li><strong>Actions Needed</strong> - What action you need on the document</li>
                                <li><strong>Route to Section</strong> - Select the destination section</li>
                                <li><strong>Employee</strong> - Select the specific staff member</li>
                            </ul>
                        </li>
                        <li>Click <strong>Submit</strong>.</li>
                    </ol>

                    <h5>For DTS Users (Accepting Guest Documents)</h5>
                    <ol>
                        <li>Navigate to <strong>Doc Tracking &gt; Guest Docs</strong> in the sidebar.</li>
                        <li>Review the list of submitted guest documents.</li>
                        <li>Click <strong>Accept</strong> to accept the document into the tracking system. This assigns a tracking code.</li>
                        <li>Or click <strong>Delete</strong> to remove invalid submissions.</li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 5. Creating Documents --}}
                <section id="create-doc" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="fluent:document-one-page-24-regular" class="icon"></iconify-icon>
                        5. Creating a New Document
                    </h4>
                    <ol>
                        <li>Click <strong>Submit New</strong> in the sidebar under Doc Tracking.</li>
                        <li>Fill in the document form:
                            <ul>
                                <li><strong>Document Type</strong> - Select the category (e.g., Memorandum, Letter, Report)</li>
                                <li><strong>Description</strong> - Document description/subject</li>
                                <li><strong>Actions Needed</strong> - What the receiving section needs to do</li>
                                <li><strong>Route to Section</strong> - Where to send the document</li>
                                <li><strong>Employee</strong> - The specific recipient (auto-populated based on section)</li>
                            </ul>
                        </li>
                        <li>Click <strong>Submit</strong>.</li>
                        <li>The system will generate a <strong>Tracking Code</strong> and <strong>QR Code</strong> for the document.</li>
                    </ol>

                    <div class="alert alert-info-custom">
                        <iconify-icon icon="solar:info-circle-linear" class="icon"></iconify-icon>
                        <div>
                            <strong>Tracking Code Format:</strong> The code follows the pattern <code>MMYY-W-NNNNN</code> where MM = month, YY = year, W = week indicator, and NNNNN = sequential number with padding.
                        </div>
                    </div>

                    <h5>After Creating a Document</h5>
                    <p>You will be taken to the document detail view where you can:</p>
                    <ul>
                        <li><strong>Print QR Slip</strong> - Print a QR sticker to attach to the physical document</li>
                        <li><strong>Edit</strong> - Modify the document description or type</li>
                        <li><strong>View Route History</strong> - See where the document has been routed</li>
                    </ul>
                </section>

                <hr class="section-divider">

                {{-- 6. Incoming Documents --}}
                <section id="incoming" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="fluent:document-search-20-regular" class="icon"></iconify-icon>
                        6. Incoming Documents
                    </h4>
                    <p>Incoming documents are those that have been forwarded to your section but <strong>not yet received/accepted</strong>.</p>
                    <ol>
                        <li>Navigate to <strong>Doc Tracking &gt; Incoming-route</strong>.</li>
                        <li>The badge count in the sidebar shows how many documents are waiting.</li>
                        <li>For each document, you can:
                            <ul>
                                <li><strong>Accept</strong> - Receive the document. It moves to Pending status.</li>
                                <li><strong>Accept & File</strong> - Accept and immediately file/keep the document.</li>
                            </ul>
                        </li>
                    </ol>
                    <div class="alert alert-warning-custom">
                        <iconify-icon icon="solar:danger-triangle-linear" class="icon"></iconify-icon>
                        <div>
                            <strong>Note:</strong> Documents left unaccepted for too long may be <strong>auto-parked</strong> by the system if this feature is enabled by the administrator.
                        </div>
                    </div>
                </section>

                <hr class="section-divider">

                {{-- 7. Pending Documents --}}
                <section id="pending" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="heroicons:document" class="icon"></iconify-icon>
                        7. Pending Documents (Received)
                    </h4>
                    <p>Pending documents are those you have received and need to act upon.</p>
                    <ol>
                        <li>Navigate to <strong>Doc Tracking &gt; Pending</strong>.</li>
                        <li>For each document, choose an action:</li>
                    </ol>

                    <div class="action-cards">
                        <div class="action-card action-forward">
                            <h6><iconify-icon icon="fluent:document-arrow-right-20-filled"></iconify-icon> Forward</h6>
                            <p>Send the document to another section for further action. Select the destination section, employee, and provide a route purpose.</p>
                        </div>
                        <div class="action-card action-keep">
                            <h6><iconify-icon icon="fluent:document-checkmark-20-regular"></iconify-icon> Keep/File</h6>
                            <p>File the document in your section. Optionally add remarks. The document's route ends here.</p>
                        </div>
                        <div class="action-card action-release">
                            <h6><iconify-icon icon="fluent:document-arrow-up-20-regular"></iconify-icon> Release</h6>
                            <p>Release the document to an external recipient. Enter the name of the person/office receiving it.</p>
                        </div>
                        <div class="action-card action-defer">
                            <h6><iconify-icon icon="material-symbols:schedule-outline"></iconify-icon> Defer</h6>
                            <p>Postpone action to a future date. Provide a reason and select the defer-until date.</p>
                        </div>
                    </div>
                </section>

                <hr class="section-divider">

                {{-- 8. Forwarding Documents --}}
                <section id="forwarding" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="fluent:document-arrow-right-20-filled" class="icon"></iconify-icon>
                        8. Forwarded Documents
                    </h4>
                    <p>This page shows documents you have forwarded to other sections that <strong>have not been received yet</strong>.</p>
                    <ol>
                        <li>Navigate to <strong>Doc Tracking &gt; Forwarded</strong>.</li>
                        <li>You can see the status of your forwarded documents.</li>
                        <li>Available actions:
                            <ul>
                                <li><strong>Edit Route</strong> - Change the destination section or employee</li>
                                <li><strong>Cancel</strong> - Cancel the forwarding (requires a reason). The document returns to its previous state.</li>
                            </ul>
                        </li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 9. Deferred Documents --}}
                <section id="deferred" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="material-symbols:map-outline" class="icon"></iconify-icon>
                        9. Deferred Documents
                    </h4>
                    <p>Deferred documents are those where action has been postponed to a later date.</p>
                    <ol>
                        <li>Navigate to <strong>Doc Tracking &gt; Deferred</strong>.</li>
                        <li>Review documents whose defer date has arrived or passed.</li>
                        <li>Actions available are the same as Pending: <strong>Forward</strong>, <strong>Keep/File</strong>, or <strong>Release</strong>.</li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 10. Batch Release --}}
                <section id="batch-release" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="et:documents" class="icon"></iconify-icon>
                        10. Batch Release
                    </h4>
                    <p>Batch Release allows you to group multiple documents and release them together to a single recipient.</p>

                    <h5>Creating a Batch</h5>
                    <ol>
                        <li>Navigate to <strong>Batch Releasing</strong> in the sidebar.</li>
                        <li>Click <strong>Add Batch Release</strong>.</li>
                        <li>Enter a <strong>Name</strong> and <strong>Description</strong> for the batch.</li>
                        <li>Click <strong>Save</strong>.</li>
                    </ol>

                    <h5>Adding Documents to a Batch</h5>
                    <ol>
                        <li>Open the batch by clicking on it.</li>
                        <li>You will see your Received/Deferred documents available to add.</li>
                        <li>Click <strong>Add</strong> next to each document you want to include.</li>
                        <li>Documents are moved from the available list to the batch list.</li>
                    </ol>

                    <h5>Finalizing a Batch</h5>
                    <ol>
                        <li>Once all documents are added, enter the <strong>Receiver Name</strong>.</li>
                        <li>Click <strong>Release Batch</strong> to finalize.</li>
                        <li>You can print the batch release form for record-keeping.</li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 11. My Documents --}}
                <section id="my-documents" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="hugeicons:invoice-03" class="icon"></iconify-icon>
                        11. My Documents
                    </h4>
                    <p>View all documents you have personally created or submitted.</p>
                    <ol>
                        <li>Navigate to <strong>My DTS &gt; My Documents</strong>.</li>
                        <li>The table shows your documents with their tracking codes, descriptions, and latest route status.</li>
                        <li>Click <strong>View</strong> to see full document details and route history.</li>
                        <li>From the document view, you can <strong>Print QR Slips</strong> in different formats:
                            <ul>
                                <li>Full Slip - Complete QR with document details</li>
                                <li>Top Right - Small QR for the top-right corner</li>
                                <li>Bottom Right - Small QR for the bottom-right corner</li>
                                <li>Bottom Left - Small QR for the bottom-left corner</li>
                            </ul>
                        </li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 12. My Section --}}
                <section id="my-section" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="stash:section-divider" class="icon"></iconify-icon>
                        12. My Section
                    </h4>
                    <p>View statistics and document history for your entire section.</p>

                    <div class="info-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:35%">Menu Item</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>DTS Statistics</strong></td>
                                    <td>Aggregated counts and charts of documents by status. Filter by date range.</td>
                                </tr>
                                <tr>
                                    <td><strong>Documents Kept</strong></td>
                                    <td>All documents that have been filed/kept in your section.</td>
                                </tr>
                                <tr>
                                    <td><strong>Parked Incoming</strong></td>
                                    <td>Documents that were auto-parked because they were not received in time.</td>
                                </tr>
                                <tr>
                                    <td><strong>Parked Pending</strong></td>
                                    <td>Documents that were auto-parked because no action was taken.</td>
                                </tr>
                                <tr>
                                    <td><strong>Parked Deferred</strong></td>
                                    <td>Deferred documents that exceeded their deferral period.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <hr class="section-divider">

                {{-- 13. QR Code Tracking --}}
                <section id="qr-codes" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="mdi:qrcode-scan" class="icon"></iconify-icon>
                        13. QR Code Tracking
                    </h4>
                    <p>The QR Code system provides a fast way to receive documents or look up their status.</p>

                    <h5>Quick Receipt (Dashboard)</h5>
                    <ol>
                        <li>On the Dashboard, locate the <strong>Quick Receipt</strong> input field.</li>
                        <li>Scan the QR code on the physical document using a barcode scanner, or type the tracking code manually.</li>
                        <li>Press <strong>Enter</strong>. If the document is routed to your section, it will be automatically accepted.</li>
                    </ol>

                    <h5>Webcam QR Scanner</h5>
                    <ol>
                        <li>Navigate to the webcam scanner page from the Dashboard.</li>
                        <li>Allow camera access when prompted.</li>
                        <li>Hold the document's QR code in front of the camera.</li>
                        <li>The system will read the code and process the receipt.</li>
                    </ol>

                    <h5>Printing QR Codes</h5>
                    <p>After creating a document, you can print QR stickers in various formats to attach to the physical document. Use the print options available on the document view page.</p>
                </section>

                <hr class="section-divider">

                {{-- 14. Search --}}
                <section id="search" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                        14. Searching Documents
                    </h4>
                    <ol>
                        <li>Use the <strong>Search bar</strong> in the top navbar.</li>
                        <li>Enter any of the following:
                            <ul>
                                <li>Tracking code (e.g., <code>0724W00001</code>)</li>
                                <li>Document description keywords</li>
                                <li>Sender name</li>
                            </ul>
                        </li>
                        <li>Press <strong>Enter</strong> to search.</li>
                        <li>Results show matching documents with tracking codes, descriptions, and sender info.</li>
                        <li>Click <strong>View</strong> to see the full document details and route history.</li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 15. Profile Settings --}}
                <section id="profile" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="solar:user-linear" class="icon"></iconify-icon>
                        15. Profile Settings
                    </h4>
                    <p>Manage your account information and password.</p>

                    <h5>Updating Email</h5>
                    <ol>
                        <li>Click your name in the top-right corner of the navbar.</li>
                        <li>Select <strong>My Profile</strong>.</li>
                        <li>Update your email address.</li>
                        <li>Click <strong>Save Changes</strong>.</li>
                    </ol>

                    <h5>Changing Password</h5>
                    <ol>
                        <li>On the Profile page, locate the <strong>Change Password</strong> card.</li>
                        <li>Enter your <strong>Current Password</strong>.</li>
                        <li>Enter and confirm your <strong>New Password</strong>.</li>
                        <li>Click <strong>Change Password</strong>.</li>
                    </ol>
                </section>

                <hr class="section-divider">

                {{-- 16. Administration --}}
                <section id="admin" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="icon-park-outline:setting-two" class="icon"></iconify-icon>
                        16. Administration
                    </h4>
                    <p>These features are only available to users with administrator roles.</p>

                    <h5>Managing Users</h5>
                    <ul>
                        <li><strong>Application &gt; Users &gt; Users List</strong> - View, create, edit, and delete user accounts</li>
                        <li>When creating a user, assign them to a section and a role</li>
                        <li>Users can be assigned to multiple sections</li>
                    </ul>

                    <h5>Managing Roles</h5>
                    <ul>
                        <li><strong>Application &gt; Users &gt; User Roles</strong> - View and manage user roles</li>
                        <li>Roles define what permissions a user has (which pages they can access)</li>
                    </ul>

                    <h5>Managing Sections</h5>
                    <ul>
                        <li><strong>System Settings &gt; Sections</strong> - Add, edit, or remove sections</li>
                        <li>Configure which sections appear in dropdown menus</li>
                        <li>Set sections as public-facing or internal</li>
                        <li>Designate record management sections</li>
                    </ul>

                    <h5>Document Types</h5>
                    <ul>
                        <li><strong>System Settings &gt; Doc Types</strong> - Manage document categories</li>
                        <li>Add new types like Memorandum, Letter, Report, etc.</li>
                    </ul>

                    <h5>DTS Settings</h5>
                    <ul>
                        <li><strong>System Settings &gt; DTS Settings</strong> - System-wide configuration:
                            <ul>
                                <li><strong>Organization DTS Code</strong> - Prefix for tracking codes</li>
                                <li><strong>Custom System Name</strong> - Displayed in the footer</li>
                                <li><strong>Number of Padding</strong> - How many digits in the tracking number</li>
                                <li><strong>Auto-Park Settings</strong> - Enable/disable and set days for auto-parking</li>
                                <li><strong>Logo Upload</strong> - Light and dark mode logos</li>
                                <li><strong>Guest Document Form</strong> - Enable/disable guest submissions</li>
                            </ul>
                        </li>
                    </ul>

                    <h5>Auto-Parking</h5>
                    <p>The system can automatically park (set aside) documents that have been inactive for too long. On the DTS Settings page, click <strong>Park Documents Now</strong> to manually trigger parking based on the configured number of days.</p>
                </section>

                <hr class="section-divider">

                {{-- 17. Status Reference --}}
                <section id="statuses" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="solar:list-check-linear" class="icon"></iconify-icon>
                        17. Document Status Reference
                    </h4>

                    <div class="info-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:8%">ID</th>
                                    <th style="width:22%">Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary">1</span></td>
                                    <td>Incoming</td>
                                    <td>Document has been forwarded to a section but not yet received</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark">2</span></td>
                                    <td>Received / Pending</td>
                                    <td>Document has been accepted and is awaiting action</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">3</span></td>
                                    <td>Filed / Kept</td>
                                    <td>Document has been filed and stored in the section</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">4</span></td>
                                    <td>Released</td>
                                    <td>Document has been released to an external recipient</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-danger">5</span></td>
                                    <td>Deferred</td>
                                    <td>Action postponed to a future date</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">6</span></td>
                                    <td>Forwarded</td>
                                    <td>Document has been forwarded to another section</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">7</span></td>
                                    <td>Incoming Parked</td>
                                    <td>Auto-parked due to no receipt action within the threshold</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">8</span></td>
                                    <td>Pending Parked</td>
                                    <td>Auto-parked due to no action taken within the threshold</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">9</span></td>
                                    <td>Deferred Parked</td>
                                    <td>Auto-parked because deferred period exceeded the threshold</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">11</span></td>
                                    <td>Batch Release</td>
                                    <td>Document is part of a batch release group</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <hr class="section-divider">

                {{-- 18. FAQ --}}
                <section id="faq" class="manual-section">
                    <h4 class="section-title">
                        <iconify-icon icon="solar:question-circle-linear" class="icon"></iconify-icon>
                        18. Frequently Asked Questions
                    </h4>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    What is a tracking code and how is it generated?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    A tracking code is a unique identifier assigned to every document in the system. It follows the format configured in DTS Settings (e.g., <code>0724W00001</code>). The code includes the month/year and a sequential number. It is automatically generated when a document is created or when a guest document is accepted.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I forward a document to multiple sections at once?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No, documents are forwarded to one section at a time. If you need to send to multiple sections, forward to the first section and have them forward it onward, or create separate document entries.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    What happens when a document is "parked"?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Parked documents are those that have been inactive beyond the configured threshold (set by the administrator). They are moved to a separate parked list and no longer appear in your active document queues. This helps keep your work area focused on current items. Parked documents can be viewed under <strong>My Section</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    How do I cancel a forwarded document?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Go to <strong>Doc Tracking &gt; Forwarded</strong>, find the document, and click the <strong>Cancel</strong> button. You must provide a reason for cancellation. The document's route will be removed and the previous route will be restored.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    I can't see certain menu items. Why?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Menu visibility is based on your role and permissions. If you need access to certain features (like administration, batch release, etc.), contact your system administrator to update your role permissions.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                    How do I switch between sections?
                                </button>
                            </h2>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If you are assigned to multiple sections, click the section name button (usually shown in green) at the top-right of the content area. A dropdown will appear with all your assigned sections. Click the one you want to switch to.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Footer --}}
                <div class="manual-footer mt-4 pt-3 border-top text-center">
                    <p class="text-secondary-light text-sm mb-1">Document Tracking System (DTS) - User's Manual</p>
                    <p class="text-secondary-light text-xs">Last updated: {{ date('F Y') }}</p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .manual-toc {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .toc-link {
        display: block;
        padding: 0.5rem 1.25rem;
        font-size: 0.78rem;
        color: var(--neutral-600);
        text-decoration: none;
        border-left: 2px solid transparent;
        transition: all 0.2s ease;
    }
    .toc-link:hover,
    .toc-link.active {
        color: var(--primary-600);
        background: var(--primary-50);
        border-left-color: var(--primary-500);
    }
    [data-theme="dark"] .toc-link {
        color: var(--neutral-400);
    }
    [data-theme="dark"] .toc-link:hover,
    [data-theme="dark"] .toc-link.active {
        color: var(--primary-300);
        background: rgba(99, 102, 241, 0.1);
    }

    .manual-content {
        line-height: 1.75;
        color: var(--neutral-700);
    }
    [data-theme="dark"] .manual-content {
        color: var(--neutral-300);
    }

    .manual-section {
        scroll-margin-top: 100px;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--neutral-800);
        margin-bottom: 1rem;
    }
    [data-theme="dark"] .section-title {
        color: var(--neutral-100);
    }
    .section-title .icon {
        font-size: 1.4rem;
        color: var(--primary-500);
    }

    .section-divider {
        margin: 2rem 0;
        border-color: var(--neutral-200);
    }
    [data-theme="dark"] .section-divider {
        border-color: var(--neutral-700);
    }

    .manual-content h5 {
        font-weight: 600;
        font-size: 1rem;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: var(--neutral-700);
    }
    [data-theme="dark"] .manual-content h5 {
        color: var(--neutral-200);
    }

    .manual-content ol, .manual-content ul {
        padding-left: 1.25rem;
    }
    .manual-content li {
        margin-bottom: 0.35rem;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 1.25rem 0;
    }
    .feature-card {
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid var(--neutral-200);
        transition: all 0.2s ease;
    }
    .feature-card:hover {
        border-color: var(--primary-200);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
    }
    [data-theme="dark"] .feature-card {
        border-color: var(--neutral-700);
    }
    .feature-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }
    .feature-card h6 {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    .feature-card p {
        font-size: 0.78rem;
        color: var(--neutral-500);
        margin-bottom: 0;
    }

    .lifecycle-flow {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin: 1rem 0;
        padding: 1rem;
        background: var(--neutral-50);
        border-radius: 12px;
    }
    [data-theme="dark"] .lifecycle-flow {
        background: var(--neutral-800);
    }
    .lifecycle-step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--neutral-200);
        font-size: 0.8125rem;
        font-weight: 500;
    }
    [data-theme="dark"] .lifecycle-step {
        background: var(--neutral-700);
        border-color: var(--neutral-600);
    }
    .step-number {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
    }
    .lifecycle-arrow {
        color: var(--neutral-400);
        font-size: 1.2rem;
    }

    .alert-info-custom,
    .alert-warning-custom {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        font-size: 0.8125rem;
        margin: 1rem 0;
    }
    .alert-info-custom {
        background: var(--primary-50);
        color: var(--primary-700);
    }
    .alert-info-custom .icon {
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .alert-warning-custom {
        background: #FEF3C7;
        color: #92400E;
    }
    .alert-warning-custom .icon {
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
        color: #D97706;
    }
    [data-theme="dark"] .alert-info-custom {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary-300);
    }
    [data-theme="dark"] .alert-warning-custom {
        background: rgba(245, 158, 11, 0.1);
        color: #FBBF24;
    }

    .action-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.75rem;
        margin: 1rem 0;
    }
    .action-card {
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid var(--neutral-200);
    }
    [data-theme="dark"] .action-card {
        border-color: var(--neutral-700);
    }
    .action-card h6 {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }
    .action-card p {
        font-size: 0.78rem;
        color: var(--neutral-500);
        margin-bottom: 0;
    }
    .action-forward h6 { color: var(--primary-600); }
    .action-keep h6 { color: var(--success-600); }
    .action-release h6 { color: #0891B2; }
    .action-defer h6 { color: var(--danger-600); }

    .info-table .table {
        font-size: 0.8125rem;
    }
    .info-table .table th {
        background: var(--neutral-50) !important;
    }
    [data-theme="dark"] .info-table .table th {
        background: var(--neutral-800) !important;
    }

    .accordion-item {
        border: 1px solid var(--neutral-200) !important;
        border-radius: 10px !important;
        margin-bottom: 0.5rem;
        overflow: hidden;
    }
    [data-theme="dark"] .accordion-item {
        border-color: var(--neutral-700) !important;
        background: var(--neutral-800);
    }
    .accordion-button {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.85rem 1.25rem;
    }
    .accordion-button:not(.collapsed) {
        background: var(--primary-50);
        color: var(--primary-700);
    }
    [data-theme="dark"] .accordion-button:not(.collapsed) {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary-300);
    }
    .accordion-body {
        font-size: 0.8125rem;
        padding: 1rem 1.25rem;
    }

    .bg-success-50 { background-color: #F0FDF4; }
    .bg-warning-50 { background-color: #FFFBEB; }
    .bg-danger-50 { background-color: #FEF2F2; }
    .bg-primary-50 { background-color: var(--primary-50); }

    .text-success-600 { color: var(--success-600); }
    .text-warning-600 { color: var(--warning-600); }
    .text-danger-600 { color: var(--danger-600); }
    .text-primary-600 { color: var(--primary-600); }

    @media print {
        .col-lg-3 { display: none !important; }
        .col-lg-9 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tocLinks = document.querySelectorAll('.toc-link');
        var sections = document.querySelectorAll('.manual-section');

        function setActiveLink() {
            var scrollPos = window.scrollY + 120;
            sections.forEach(function(section, index) {
                if (section.offsetTop <= scrollPos && section.offsetTop + section.offsetHeight > scrollPos) {
                    tocLinks.forEach(function(link) { link.classList.remove('active'); });
                    tocLinks[index].classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', setActiveLink);

        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
</script>
@endsection
