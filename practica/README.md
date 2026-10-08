# Café de Origen — práctica académica

Equipo: **josesalomon**. Repositorio público: https://github.com/Matsukaz-e/tienda-josesalomon

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

El 7 de octubre de 2026 se completó la prueba desde un móvil con datos, sin iniciar sesión. Se conservan capturas de los pedidos iniciales #37 y #38, Tiempo real (2 purchase) y DebugView. En la revisión posterior existe además #39; no se hicieron pedidos nuevos durante la auditoría.

El ID se comprobó en los ajustes de WooCommerce, en el flujo Web de Analytics y en el HTML servido a visitantes. La cuenta de Analytics se llama josesalomon, con propiedad Café de Origen - GA4, Colombia/COP.

Informes provisionales del 7 de octubre: café variable 3 vistas, kit 2 y Huila 1; los tres empatan con una unidad comprada. Exploración cerrada «Embudo josesalomon - 4 eventos», con view_item → add_to_cart → begin_checkout → purchase: 1 usuario activo en cada paso, sin abandono. Dispositivos: 3 de 5 usuarios activos móviles (60%), incluidos visitantes de prueba y simulación móvil. La muestra no permite conclusiones comerciales reales.

Pendientes: comparar fuente/medio de la campaña clase/practica/cafe_origen frente a directo cuando la atribución se estabilice (24–48 horas), registrar la URL en la hoja del curso cuando el profesor facilite el enlace y realizar el intercambio de tráfico entre equipos. Las pruebas documentadas fueron como visitante; no se conserva evidencia específica del modo incógnito.

Al reabrir el Codespace revisa que 8080 siga público y 8081 privado. Mantén el proyecto en su directorio original para conservar la asociación de Docker Compose con los volúmenes existentes; renombrar el repositorio no requiere mover ese directorio.
