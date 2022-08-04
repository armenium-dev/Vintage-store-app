DROP VIEW IF EXISTS vintage_items;
CREATE VIEW vintage_items AS SELECT
    p.id,
    p.shop_id,
    p.product_id,
    v.variant_id,
    p.title AS product_title,
    v.title AS variant_title,
    v.inventory_quantity,
    v.price,
    v.size,
    v.color,
    p.image
FROM products p
LEFT JOIN variants v ON p.product_id = v.product_id
WHERE p.product_id NOT IN (SELECT product_id FROM tags WHERE tag IN ('MARKET', 'REWORK'))
    AND p.is_mystery = 0
    AND p.status = 'active'
    AND p.title NOT LIKE '%REWORK%'
    AND (p.link_asos != '' OR p.link_depop != '')
    AND v.inventory_quantity = 1
    AND (p.body LIKE '%A* Vintage Quality%' OR body LIKE '%A Vintage Quality%')
    AND v.price BETWEEN 15 AND 39
ORDER BY v.price;


