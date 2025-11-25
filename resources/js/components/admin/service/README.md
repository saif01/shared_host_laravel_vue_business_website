# Service Management Components

This directory contains the service management components split for better maintainability.

## File Structure

```
service/
├── AdminServices.vue           # Main component (table/list view) ~300 lines
├── ServiceFormDialog.vue       # Create/Edit form dialog ~700 lines  
├── ServiceDetailsDialog.vue    # View/details dialog ~200 lines
└── README.md                   # This file
```

## Component Responsibilities

### AdminServices.vue
- Main table/list view of services
- Search and filter functionality
- Pagination
- Action buttons (view, edit, delete)
- Opens form and details dialogs

### ServiceFormDialog.vue
- Create/Edit service form
- Three tabs: Basic Info, Content, SEO & Meta
- Rich text editor integration
- Image upload handling
- Form validation and submission

### ServiceDetailsDialog.vue
- Read-only view of service details
- Three tabs: Basic Info, Content, SEO & Meta
- Display all service information
- Edit button to switch to form dialog

## Usage

```vue
<template>
    <AdminServices />
</template>

<script>
import AdminServices from './components/admin/service/AdminServices.vue';

export default {
    components: {
        AdminServices
    }
};
</script>
```

## Future Improvements

- Extract tab components if needed (ServiceFormBasicTab, ServiceFormContentTab, ServiceFormSEOTab)
- Extract form validation into composables
- Extract image upload logic into composables
- Create reusable editor component

