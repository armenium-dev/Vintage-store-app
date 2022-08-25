DROP VIEW IF EXISTS vintage_handpick_items;
CREATE VIEW vintage_handpick_items AS SELECT
    p.id,
    p.shop_id,
    p.product_id,
    v.variant_id,
    p.title AS product_title,
    v.title AS variant_title,
    v.inventory_quantity,
    v.price,
	v.option1,
	v.option2,
	v.option3,
    p.image
FROM products p
LEFT JOIN variants v ON p.product_id = v.product_id
LEFT JOIN tags t ON t.product_id = p.product_id
WHERE p.product_id NOT IN (SELECT product_id FROM tags WHERE tag IN ('MARKET', 'REWORK'))
  -- AND p.product_id NOT IN (SELECT product_id FROM mystery_boxes)
  AND p.is_mystery = 0
  AND p.status = 'active'
  AND p.deleted_at IS NULL
  AND p.online_store_url IS NOT NULL
  AND p.title NOT LIKE '%REWORK%'
  -- AND (p.link_asos != '' OR p.link_depop != '')
  AND t.tag = 'GG'
  AND v.inventory_quantity = 1
  AND (
        p.body LIKE '%A* Vintage Quality%' OR
        p.body LIKE '%A Vintage Quality%' OR
        p.body LIKE '%A* Quality Vintage%' OR
        p.body LIKE '%A Quality Vintage%' OR
        p.body LIKE '%A* Quality%' OR
        p.body LIKE '%A Quality%'
        )
    AND v.price BETWEEN 30 AND 61
ORDER BY p.created_at;

