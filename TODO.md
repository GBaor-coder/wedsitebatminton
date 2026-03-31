# Admin Dashboard UI Update Tasks

**Current working directory:** c:/xampp/htdocs/websitebatminton

## Plan Steps:
- [ ] Step 1: Edit resources/views/admin/partials/header.php
  - Remove MAIN quick access buttons row
  - Add 'Bài viết' and 'Tin nhắn' to sidebar with icons and active logic
- [ ] Step 2: Verify changes on /admin/dashboard
- [ ] Step 3: Test sidebar active states on /admin/posts and /admin/contacts/messenger

**Status:** 
- [x] Step 1: Edited resources/views/admin/partials/header.php (removed MAIN buttons, added sidebar items)

**Status:** Complete ✅

All changes implemented:
- Removed 6 MAIN buttons from header.php
- Added "Bài viết" (fa-newspaper, /admin/posts) and "Tin nhắn" (fa-envelope, /admin/contacts/messenger) to sidebar with matching styles/active logic

Visit http://localhost/websitebatminton/admin/dashboard to see updates. Test navigation to /admin/posts and /admin/contacts/messenger for active sidebar highlighting.
