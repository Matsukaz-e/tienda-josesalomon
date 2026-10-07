<?php
// Ejecutar con WP-CLI después de instalar WordPress y activar WooCommerce.
if (!defined('WP_CLI') || !WP_CLI) { exit(1); }
if (!class_exists('WooCommerce')) { WP_CLI::error('WooCommerce no está activo.'); }
if (get_option('practica_cafe_configurada')) { WP_CLI::success('La configuración ya se aplicó.'); return; }
$catalogo = json_decode(file_get_contents('/tmp/catalogo-cafe.json'), true, 512, JSON_THROW_ON_ERROR);
update_option('timezone_string', 'America/Bogota');
update_option('woocommerce_default_country', 'CO');
update_option('woocommerce_currency', 'COP');
update_option('woocommerce_price_num_decimals', 0);
update_option('woocommerce_calc_taxes', 'no');
update_option('woocommerce_enable_coupons', 'yes');
update_option('woocommerce_ship_to_countries', 'specific');
update_option('woocommerce_specific_ship_to_countries', array('CO'));
WC_Install::create_pages();
foreach (WC()->payment_gateways()->payment_gateways() as $id => $gateway) {
    $settings = get_option('woocommerce_' . $id . '_settings', array());
    $settings['enabled'] = $id === 'cod' ? 'yes' : 'no';
    if ($id === 'cod') {
        $settings['title'] = 'Contra reembolso (prueba)';
        $settings['description'] = 'Pedido académico de demostración. No se realizará cobro ni envío.';
        $settings['instructions'] = 'Pedido de prueba. No hay cobro ni entrega real.';
        $settings['enable_for_methods'] = array();
    }
    update_option('woocommerce_' . $id . '_settings', $settings);
}
$zone_id = 0;
foreach (WC_Shipping_Zones::get_zones() as $zone) {
    if ($zone['zone_name'] === 'Colombia') { $zone_id = $zone['zone_id']; break; }
}
$zone = new WC_Shipping_Zone($zone_id ?: null);
$zone->set_zone_name('Colombia');
$zone->set_zone_order(1);
$zone->set_locations(array(array('code' => 'CO', 'type' => 'country')));
$zone->save();
$methods = array();
foreach ($zone->get_shipping_methods() as $method) { $methods[$method->id] = $method->instance_id; }
$flat = $methods['flat_rate'] ?? $zone->add_shipping_method('flat_rate');
$free = $methods['free_shipping'] ?? $zone->add_shipping_method('free_shipping');
update_option('woocommerce_flat_rate_' . $flat . '_settings', array('title'=>'Tarifa plana Colombia','tax_status'=>'none','cost'=>'12000'));
update_option('woocommerce_free_shipping_' . $free . '_settings', array('title'=>'Envío gratuito','requires'=>'min_amount','min_amount'=>'100000','ignore_discounts'=>'no'));
$coupon_id = wc_get_coupon_id_by_code('BIENVENIDA10');
$coupon = new WC_Coupon($coupon_id);
$coupon->set_code('BIENVENIDA10');
$coupon->set_discount_type('percent');
$coupon->set_amount(10);
$coupon->set_description('Cupón de demostración de la práctica académica.');
$coupon->save();
foreach ($catalogo as $r) {
    $id = wc_get_product_id_by_sku($r['SKU']);
    if ($id) { WP_CLI::log('SKU existente, conservado: ' . $r['SKU']); continue; }
    if ($r['Type'] === 'variation') {
        $parent = wc_get_product_id_by_sku($r['Parent']);
        if (!$parent) { WP_CLI::error('No se encontró el producto padre.'); }
        $product = new WC_Product_Variation();
        $product->set_parent_id($parent);
        $product->set_attributes(array(sanitize_title($r['Attribute 1 name'])=>$r['Attribute 1 value(s)']));
    } elseif ($r['Type'] === 'variable') {
        $product = new WC_Product_Variable();
        $attribute = new WC_Product_Attribute();
        $attribute->set_id(0);
        $attribute->set_name($r['Attribute 1 name']);
        $attribute->set_options(array_map('trim', explode(',', $r['Attribute 1 value(s)'])));
        $attribute->set_visible(true);
        $attribute->set_variation(true);
        $product->set_attributes(array($attribute));
    } else { $product = new WC_Product_Simple(); }
    $product->set_name($r['Name']);
    $product->set_sku($r['SKU']);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description($r['Description']);
    $product->set_short_description($r['Short description']);
    $product->set_tax_status('none');
    if ($r['Regular price'] !== '') { $product->set_regular_price($r['Regular price']); }
    if ($r['Sale price'] !== '') { $product->set_sale_price($r['Sale price']); }
    if ($r['Stock'] !== '') { $product->set_manage_stock(true); $product->set_stock_quantity((int)$r['Stock']); }
    $product->set_stock_status('instock');
    if ($r['Categories'] !== '') {
        $term = term_exists($r['Categories'], 'product_cat');
        if (!$term) { $term = wp_insert_term($r['Categories'], 'product_cat'); }
        if (is_wp_error($term)) { WP_CLI::error($term->get_error_message()); }
        $product->set_category_ids(array((int)$term['term_id']));
    }
    $product->save();
}
$parent_id = wc_get_product_id_by_sku('CAF-VAR-001');
WC_Product_Variable::sync($parent_id);
wc_delete_product_transients($parent_id);
update_option('practica_cafe_configurada', 1);
WP_CLI::success('Configurados catálogo, cupón, pagos, moneda y envíos. Imágenes y GA4 pendientes.');
