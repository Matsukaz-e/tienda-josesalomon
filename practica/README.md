# Café de Origen — práctica académica

Tienda de demostración con WordPress, WooCommerce, MariaDB y Google Analytics 4. Los productos, imágenes y pedidos son ficticios: no se realizan cobros ni entregas.

## Acceso

- Tienda: https://expert-yodel-w4jgq7jjw4xf9g6g-8080.app.github.dev/
- Puerto 8080: público. Puerto 8081 (phpMyAdmin): privado.
- Analytics: ID de medición G-8PMRXFDLHQ.
- Colombia, COP, zona horaria America/Bogota.

## Configuración realizada

8 productos simples, 1 variable con 3 presentaciones y existencias; 3 categorías; prensa francesa en oferta; cupón BIENVENIDA10 (10%). Envío a Colombia: tarifa $12.000 o gratis desde $100.000 después del descuento. Pago contra reembolso de prueba. Tema de bloques Café de Origen, basado en Twenty Twenty-Five.

## Archivos reproducibles

`catalogo-cafe.json` contiene los productos; `configurar-tienda.php` configura WooCommerce y `terminar-tienda.php` prepara las páginas e imágenes; `cafe-origen/` es el tema hijo. Los scripts se ejecutan con WP-CLI dentro del contenedor de WordPress, después de instalar WooCommerce y su integración oficial de Analytics. Requieren los archivos del catálogo y las imágenes importadas con los nombres cafe, prensa, taza, kit y duo. Guarda Enlaces permanentes desde el administrador para generar las reglas de Apache.

El ID de Analytics debe configurarse en WooCommerce > Ajustes > Integración. El complemento excluye al administrador: verifica los eventos como visitante.

## Persistencia y pruebas

Git conserva estos archivos de configuración; no respalda la base de datos ni el volumen de imágenes. Exporta la base de datos y conserva los archivos de WordPress si vas a eliminar el Codespace. No uses `docker compose down -v`: elimina los volúmenes. Detén el Codespace cuando no lo uses y arráncalo antes de presentar la tienda.

Para la entrega, verifica desde un móvil con datos, recoge capturas de los pedidos, Tiempo real y DebugView, y revisa los informes de Analytics después de 24–48 horas. Los datos de prueba no permiten conclusiones comerciales reales.
