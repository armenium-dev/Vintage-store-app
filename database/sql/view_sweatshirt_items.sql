DROP VIEW IF EXISTS sweatshirt_items;
CREATE VIEW sweatshirt_items AS SELECT
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
WHERE p.product_id NOT IN (SELECT product_id FROM tags WHERE tag IN ('MARKET', 'REWORK'))
    AND p.is_mystery = 0
    AND p.status = 'active'
  AND p.deleted_at IS NULL
    -- AND (p.link_asos != '' OR p.link_depop != '')
    AND v.inventory_quantity = 1
    AND v.price = 12
    AND p.title LIKE '%Sweatshirt%'
  AND (
			p.body LIKE '%A* Vintage Quality%' OR
			p.body LIKE '%A Vintage Quality%' OR
			p.body LIKE '%A* Quality Vintage%' OR
			p.body LIKE '%A Quality Vintage%' OR
			p.body LIKE '%A* Quality%' OR
			p.body LIKE '%A Quality%'
	)
ORDER BY v.price;


