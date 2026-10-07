<?php
if (!defined('WP_CLI') || !WP_CLI) { exit(1); }
$base = 'https://expert-yodel-w4jgq7jjw4xf9g6g-8080.app.github.dev';
update_option('home', $base);
update_option('siteurl', $base);
update_option('blogdescription', 'Café colombiano, accesorios y regalos');
update_option('permalink_structure', '/%postname%/');
update_option('woocommerce_price_thousand_sep', '.');
update_option('woocommerce_price_decimal_sep', ',');
foreach (array('shop'=>array('Tienda','tienda'), 'cart'=>array('Carrito','carrito'), 'checkout'=>array('Finalizar compra','finalizar-compra'), 'myaccount'=>array('Mi cuenta','mi-cuenta')) as $key=>$page) {
 $id = (int)get_option('woocommerce_'.$key.'_page_id');
 $data = array('ID'=>$id,'post_title'=>$page[0],'post_name'=>$page[1]);
 if ($key==='cart') { $data['post_content']='[woocommerce_cart]'; }
 if ($key==='checkout') { $data['post_content']='[woocommerce_checkout]'; }
 if ($id) { wp_update_post($data); }
}
$images = array('cafe'=>array('CAF-001','CAF-002','CAF-003','CAF-004','CAF-VAR-001','CAF-VAR-001-250','CAF-VAR-001-500','CAF-VAR-001-1000'), 'prensa'=>array('ACC-001'), 'taza'=>array('ACC-002'), 'kit'=>array('REG-001'), 'duo'=>array('REG-002'));
foreach ($images as $name=>$skus) {
 $attachments=get_posts(array('post_type'=>'attachment','post_status'=>'inherit','name'=>$name,'posts_per_page'=>1));
 if (!$attachments) { WP_CLI::error('Falta subir la imagen '.$name); }
 $image_id=$attachments[0]->ID;
 update_post_meta($image_id,'_wp_attachment_image_alt','Imagen ilustrativa generada para la tienda de demostración: '.$name);
 foreach ($skus as $sku) { $p=wc_get_product(wc_get_product_id_by_sku($sku)); $p->set_image_id($image_id); $p->save(); }
}
$content=<<<'CAFE_HOME'
<!-- wp:group {"style":{"spacing":{"padding":{"top":"72px","bottom":"56px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:72px;padding-bottom:56px"><!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">DE COLOMBIA A TU TAZA</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Cada origen cuenta<br>una historia.</h1><!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"large"} --><p class="has-large-font-size">Descubre cafés de Huila, Nariño, Sierra Nevada y Antioquia. Encuentra tu próxima taza favorita.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/tienda/">Explorar la tienda</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->
<!-- wp:group {"backgroundColor":"forest","textColor":"cream","style":{"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}}},"layout":{"type":"constrained"}} --><div class="wp-block-group has-cream-color has-forest-background-color has-text-color has-background" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Envío a Colombia · Gratis desde $100.000 · 10% con BIENVENIDA10</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"56px","bottom":"56px"}}},"layout":{"type":"constrained"}} --><div class="wp-block-group" style="padding-top:56px;padding-bottom:56px"><!-- wp:heading --><h2 class="wp-block-heading">Elige tu ritual</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Cafés de origen, accesorios para prepararlos y regalos para compartir.</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[products limit="9" columns="3" orderby="menu_order" order="ASC"]<!-- /wp:shortcode --></div><!-- /wp:group -->
CAFE_HOME;
$home=get_page_by_path('inicio');
$home_id=wp_insert_post(array('ID'=>$home?$home->ID:0,'post_type'=>'page','post_status'=>'publish','post_title'=>'Inicio','post_name'=>'inicio','post_content'=>$content));
update_option('show_on_front','page');
update_option('page_on_front',$home_id);
$priv=get_page_by_path('privacidad-de-la-practica');
$priv_id=wp_insert_post(array('ID'=>$priv?$priv->ID:0,'post_type'=>'page','post_status'=>'publish','post_title'=>'Privacidad de la práctica','post_name'=>'privacidad-de-la-practica','post_content'=>'<!-- wp:paragraph --><p>Esta es una tienda académica de demostración. Los productos y las imágenes son ilustrativos; los pedidos son simulados y no producen cobros ni entregas. Usa datos ficticios al probar la compra.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>WordPress guarda los datos que se introducen en los pedidos. Google Analytics 4 registra interacciones de navegación y compra para analizar esta práctica, mediante cookies y medición de eventos. Consulta también la política de privacidad de Google (https://policies.google.com/privacy). No incluyas datos personales en enlaces, nombres de eventos ni parámetros de Analytics.</p><!-- /wp:paragraph -->'));
update_option('wp_page_for_privacy_policy',$priv_id);
// Guardar enlaces permanentes desde el administrador para generar reglas de Apache.
WP_CLI::success('Imágenes asignadas y páginas de la tienda preparadas.');
