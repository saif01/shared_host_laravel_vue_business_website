# Product Fields Verification

This document verifies that all form fields in `AdminProducts.vue` match the database migration and controller validation.

## Migration Fields (`2025_11_22_200601_create_products_table.php`)

| Field | Type | Nullable | Default | Form Field | Controller Validation |
|-------|------|----------|---------|------------|----------------------|
| `id` | bigint | No | Auto | N/A (auto) | N/A |
| `title` | string(255) | No | - | ✓ `form.title` | `required\|string\|max:255` |
| `slug` | string(255) | No | - | ✓ `form.slug` | `required\|string\|max:255\|unique:products` |
| `sku` | string(255) | Yes | - | ✓ `form.sku` | `nullable\|string\|max:255\|unique:products` |
| `short_description` | text | Yes | - | ✓ `form.short_description` | `nullable\|string` |
| `description` | text | Yes | - | ✓ `form.description` | `nullable\|string` |
| `thumbnail` | string(255) | Yes | - | ✓ `form.thumbnail` | `nullable\|string` |
| `images` | json | Yes | - | ✓ `form.images` | `nullable\|array` |
| `price` | decimal(10,2) | Yes | - | ✓ `form.price` | `nullable\|numeric` |
| `price_range` | string(255) | Yes | - | ✓ `form.price_range` | `nullable\|string` |
| `show_price` | boolean | No | `true` | ✓ `form.show_price` | `boolean` |
| `specifications` | json | Yes | - | ✓ `specificationsList` | `nullable\|array` |
| `downloads` | json | Yes | - | ✓ `downloadsList` | `nullable\|array` |
| `meta_title` | string(255) | Yes | - | ✓ `form.meta_title` | `nullable\|string` |
| `meta_description` | text | Yes | - | ✓ `form.meta_description` | `nullable\|string` |
| `meta_keywords` | string(255) | Yes | - | ✓ `form.meta_keywords` | `nullable\|string` |
| `og_image` | string(255) | Yes | - | ✓ `form.og_image` | `nullable\|string` |
| `published` | boolean | No | `false` | ✓ `form.published` | `boolean` |
| `featured` | boolean | No | `false` | ✓ `form.featured` | `boolean` |
| `stock` | integer | Yes | - | ✓ `form.stock` | `nullable\|integer` ✓ |
| `order` | integer | No | `0` | ✓ `form.order` | `nullable\|integer` ✓ |
| `created_at` | timestamp | No | Auto | N/A (auto) | N/A |
| `updated_at` | timestamp | No | Auto | N/A (auto) | N/A |
| `deleted_at` | timestamp | Yes | - | N/A (soft delete) | N/A |

## Additional Form Fields (Not in Migration - Handled via Relationships)

| Field | Type | Storage | Form Field | Controller Handling |
|-------|------|---------|------------|---------------------|
| `category_ids` | array | `category_product` pivot | ✓ `form.category_ids` | Synced via `categories()` relationship |
| `tag_names` | array | `tag_product` pivot | ✓ `form.tag_names` | Synced via `tags()` relationship, auto-creates tags |

## Special Fields (Stored in `specifications` JSON)

| Field | Type | Storage | Form Field | Controller Handling |
|-------|------|---------|------------|---------------------|
| `key_features` | array | `specifications._key_features` | ✓ `featuresList` | Merged into `specifications` JSON |
| `faqs` | array | `specifications._faqs` | ✓ `faqsList` | Merged into `specifications` JSON |
| `warranty_info` | object | `specifications._warranty_info` | ✓ `warrantyInfo` | Merged into `specifications` JSON |

## Form Tabs in AdminProducts.vue

1. **Basic Info** - `title`, `slug`, `sku`, `short_description`, `description`
2. **Media** - `thumbnail`, `images`
3. **Pricing** - `price`, `price_range`, `show_price`
4. **Categories & Tags** - `category_ids`, `tag_names`
5. **Specifications** - `specificationsList` (dynamic key-value pairs)
6. **Features** - `featuresList` (stored as `_key_features` in specifications)
7. **Downloads** - `downloadsList` (stored in `downloads` JSON)
8. **FAQs** - `faqsList` (stored as `_faqs` in specifications)
9. **Warranty & Service** - `warrantyInfo` (stored as `_warranty_info` in specifications)
10. **SEO** - `meta_title`, `meta_description`, `meta_keywords`, `og_image`
11. **Settings** - `published`, `featured`, `stock`, `order`

## Verification Status

✅ **All migration fields are present in the form**
✅ **All form fields match controller validation rules**
✅ **Controller validation updated to match migration (stock and order are nullable)**
✅ **Special fields (features, FAQs, warranty) are correctly stored in specifications JSON**
✅ **Relationship fields (categories, tags) are correctly handled via pivot tables**

## Notes

- The `order` field has a default value of `0` in the migration, but validation allows `nullable|integer` to handle cases where the form might send null (database will use default).
- The `stock` field is nullable in the migration, and validation correctly allows `nullable|integer`.
- Special fields (`key_features`, `faqs`, `warranty_info`) are stored with `_` prefix in the `specifications` JSON field to distinguish them from regular specifications.
- Categories and tags are managed via many-to-many relationships and are not direct columns in the products table.

