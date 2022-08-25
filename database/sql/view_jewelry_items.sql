#DROP VIEW IF EXISTS jewelry_items;
CREATE VIEW jewelry_items AS SELECT
    p.id,
    p.shop_id,
    p.product_id,
    v.variant_id,
    p.title AS product_title,
    v.title AS variant_title,
    t.tag,
    v.inventory_quantity,
    v.price,
    v.option1,
    v.option2,
    v.option3,
    p.image
FROM products p
LEFT JOIN variants v ON p.product_id = v.product_id
LEFT JOIN tags t ON t.product_id = p.product_id
WHERE p.product_id NOT IN (SELECT product_id FROM tags WHERE tag = 'MARKET')
  AND p.is_mystery = 0
  AND p.status = 'active'
  AND p.deleted_at IS NULL
  AND p.online_store_url IS NOT NULL
  AND t.tag IN ('Necklaces', 'Rings')
  AND v.inventory_quantity > 0
ORDER BY t.tag, p.created_at;


