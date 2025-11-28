# 🧪 Testing Guide - Documentation Feature

## Prerequisites

- ✅ Laravel server running on http://127.0.0.1:8000
- ✅ Database migrations executed
- ✅ User account created and logged in
- ✅ Browser with JavaScript enabled

---

## 📋 SRS Document Testing

### Test 1: Create SRS Document

**Steps:**
1. Navigate to http://127.0.0.1:8000
2. Click the green "📄 Create Documentation" button
3. Hover and select "📋 Create SRS Document"
4. Fill in the form:
   - Title: "E-Commerce Platform SRS"
   - Description: "Requirements for building an e-commerce platform"
   - Project Overview: "Modern, scalable e-commerce platform"
   - Scope: "User authentication, product catalog, shopping cart, checkout"
   - Constraints: "Must support 10,000 concurrent users"
   - Assumptions: "Users have modern browsers"
5. Click "✅ Create SRS" button

**Expected Result:**
- Redirected to edit page for new SRS
- Form pre-filled with provided information
- Add Requirement button visible
- Success message displayed

---

### Test 2: Add Functional Requirements

**Steps:**
1. On the SRS edit page, click "+ Add Requirement"
2. Fill in the requirement:
   - Requirement ID: REQ-001
   - Title: "User Registration"
   - Description: "Users can register with email and password"
   - Priority: High
3. Click "+ Add UX Item" (in same requirement)
4. Add UX items:
   - "Responsive design - works on mobile"
   - "Email validation feedback"
   - "Password strength indicator"
5. Click "+ Add Requirement" again
6. Add second requirement:
   - Requirement ID: REQ-002
   - Title: "Product Search"
   - Description: "Users can search products by name, category, price range"
   - Priority: Critical
7. Add UX items to REQ-002:
   - "Real-time search results"
   - "Filter refinement sidebar"
   - "Search suggestions dropdown"

**Expected Result:**
- Two requirement items visible on page
- Each has add UX item button
- UX items display with remove buttons
- No page reload when adding items
- Professional styling applied

---

### Test 3: Manage Requirements (Edit/Remove)

**Steps:**
1. On requirement REQ-002, change priority to "Medium"
2. Click "+ Add UX Item" and add: "Dark mode support"
3. Remove the "Search suggestions dropdown" UX item by clicking ✕
4. Click "+ Add Requirement" and add REQ-003
5. Immediately click "Remove Requirement" on REQ-003
6. Verify REQ-001 and REQ-002 remain

**Expected Result:**
- Priority changes reflected immediately
- UX items added and removed without errors
- Requirements can be added and removed
- Form state properly maintained

---

### Test 4: Save SRS Document

**Steps:**
1. With filled SRS (title, requirements, UX items), click "💾 Save SRS"
2. Wait for page to process
3. Should see success message

**Expected Result:**
- Form validation passes
- Document saved to database
- Redirected to edit page
- Success message displayed
- Data persists on reload

---

### Test 5: Export SRS as PDF

**Steps:**
1. On the edit page, click "📥 Export PDF"
2. PDF should download

**Expected Result:**
- PDF downloads with name "SRS_[title].pdf"
- PDF contains:
  - Document header with title and dates
  - All sections (Overview, Scope, Constraints, Assumptions)
  - All functional requirements with:
    - Requirement ID and Title
    - Priority badge (color-coded)
    - Description
    - UX Considerations list
  - Professional formatting and layout
  - Created/Updated timestamps

---

### Test 6: View SRS List

**Steps:**
1. From dropdown menu, click "📑 View SRS Documents"
2. Or navigate to http://127.0.0.1:8000/documentation/srs

**Expected Result:**
- List of all user's SRS documents displayed
- Each document shows:
  - Title
  - Description preview
  - Number of requirements
  - Creation date
  - Edit, PDF, Delete buttons
- Can click Edit or PDF from list

---

### Test 7: Edit Existing SRS

**Steps:**
1. On SRS list, click "✏️ Edit" for your document
2. Change title to "E-Commerce Platform SRS v2"
3. Add a new requirement REQ-004
4. Click "💾 Save SRS"

**Expected Result:**
- Changes saved successfully
- Redirected to edit page
- Title updated in heading
- New requirement persists
- List shows updated title

---

### Test 8: Delete SRS

**Steps:**
1. On SRS list, click "🗑️ Delete" button
2. Confirm deletion in dialog

**Expected Result:**
- Document removed from database
- Removed from list
- Success message displayed
- User redirected to SRS list

---

## 🧹 SDD Testing (Retired)

SDD creation, component management, diagram tooling, and PDF export were sunset in November 2025. All related manual tests (historically Tests 9–20) were removed. For historical reference, check the repository tag `release/v1.0`.

---

## 🔒 Authorization Testing

### Test 9: Document Ownership

**Steps:**
1. Create SRS document as User A
2. Log out
3. Create new account (User B)
4. Log in as User B
5. Try to access User A's SRS directly via URL:
   `/documentation/srs/{user-a-document-id}/edit`

**Expected Result:**
- Access denied
- 403 Forbidden error or redirect
- User B cannot modify User A's documents

---

## 🔧 Edge Cases & Error Handling

### Test 10: Empty Fields Validation

**Steps:**
1. Try to create SRS without title
2. Try to add requirement without title
3. Try to add UX item without text

**Expected Result:**
- Validation errors displayed
- Form not submitted
- User-friendly error messages

---

### Test 11: Long Text Handling

**Steps:**
1. Add requirement with very long description (>1000 chars)
2. Add many UX items (10+)
3. Export to PDF

**Expected Result:**
-- Text wraps properly
-- No layout issues
-- PDF renders correctly
-- All content visible

---

### Test 12: Special Characters in Requirements

**Steps:**
1. Add a requirement with special characters in the Requirement ID (e.g., `REQ-@Login#123`)
2. Include emojis and punctuation in the requirement description and UX considerations
3. Save the SRS and export the PDF

**Expected Result:**
- Special characters remain intact in the web UI
- PDF export shows the same characters without encoding issues
- No validation errors triggered solely by the characters

---

## 📱 Responsive Design Testing

### Test 13: Mobile View

**Steps:**
1. Open the SRS edit page on a mobile browser (or use DevTools)
2. Try to:
   - Scroll through requirements
   - Add/remove UX items
   - Click buttons
   - View forms

**Expected Result:**
- Layout adapts to screen size
- All buttons clickable
- Forms readable
-- No horizontal scrolling needed
-- Touch-friendly spacing

---

## 🎨 Visual Quality Testing

### Test 14: UI/UX Polish

**Steps:**
1. Review colors and contrast
2. Check button hover states
3. Verify spacing and alignment
4. Check font readability
-- Review PDF styling

**Expected Result:**
- Professional appearance
- Consistent color scheme
- Clear visual hierarchy
- Proper spacing throughout
- PDF looks print-ready

---

## 📊 Data Persistence Testing

### Test 15: Database Integrity

**Steps:**
1. Create SRS with requirements
2. Check database directly (or through Laravel):
   ```bash
   php artisan tinker
   >>> SrsDocument::first()
   >>> SrsDocument::first()->functionalRequirements
   ```

**Expected Result:**
- Data correctly stored in database
- Relationships work properly
- JSON columns properly formatted
- No data loss

---

## ⚡ Performance Testing

### Test 16: Load with Multiple Documents

**Steps:**
1. Create 5-10 SRS documents
2. Populate each with multiple requirements and UX considerations
3. Load the dashboard and documentation list pages
4. Load multiple edit pages back-to-back

**Expected Result:**

---

## 🎉 Final Verification Checklist

- [ ] SRS: Create, Read, Update, Delete ✅
- [ ] SRS: Functional Requirements Management ✅
- [ ] SRS: UX Considerations (Add/Remove) ✅
- [ ] SRS: PDF Export ✅
- [ ] Authorization: Users can only access own documents ✅
- [ ] Validation: Form errors handled properly ✅
- [ ] UI: Responsive design works ✅
- [ ] UI: Professional appearance ✅
- [ ] Database: Data properly persisted ✅
- [ ] Performance: Pages load quickly ✅
- [ ] SDD functionality retired (no manual testing required) ✅

---

**Testing Guide Version**: 1.1
**Last Updated**: November 12, 2025
**Status**: Ready for SRS Testing ✅
