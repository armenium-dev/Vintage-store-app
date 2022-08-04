SELECT @@sql_mode;
SET SESSION sql_mode = sys.list_drop(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY');
SET sql_mode = sys.list_drop(@@sql_mode, 'ONLY_FULL_GROUP_BY');
SET @@sql_mode = sys.list_drop(@@sql_mode, 'ONLY_FULL_GROUP_BY');

SELECT @@GLOBAL.sql_mode;
SET GLOBAL sql_mode = sys.list_drop(@@GLOBAL.sql_mode, 'ONLY_FULL_GROUP_BY');

UPDATE products SET p_updated_at = NULL WHERE p_updated_at IS NOT NULL;

SELECT * FROM products WHERE link_asos != '' OR link_depop != '';

SELECT * FROM orders WHERE data->> "$.name" = '#6889';

#£12 sweatshirt items
SELECT
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
  AND (p.link_asos != '' OR p.link_depop != '')
  AND v.inventory_quantity = 1
  AND v.price = 12
  AND p.title LIKE '%Sweatshirt%'
  AND (p.body LIKE '%A* Vintage Quality%' OR body LIKE '%A Vintage Quality%')
ORDER BY v.price;

#Vintage Handpick Items
SELECT
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
         LEFT JOIN tags t ON t.product_id = p.product_id
WHERE p.product_id NOT IN (SELECT product_id FROM tags WHERE tag IN ('MARKET', 'REWORK'))
  AND p.is_mystery = 0
  AND p.status = 'active'
  AND p.title NOT LIKE '%REWORK%'
  AND (p.link_asos != '' OR p.link_depop != '')
  AND t.tag = 'GG'
  AND v.inventory_quantity = 1
  AND (p.body LIKE '%A* Vintage Quality%' OR body LIKE '%A Vintage Quality%')
  AND v.price BETWEEN 30 AND 61
ORDER BY v.price;

#Vintage Items
SELECT
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

#Rework items
SELECT
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
WHERE p.product_id NOT IN (SELECT product_id FROM tags WHERE tag = 'MARKET')
  AND p.is_mystery = 0
  AND p.status = 'active'
  AND p.title LIKE '%REWORK%'
  AND (p.link_asos != '' OR p.link_depop != '')
  AND v.inventory_quantity = 1
  AND (p.body LIKE '%A* Vintage Quality%' OR body LIKE '%A Vintage Quality%')
ORDER BY v.price;
